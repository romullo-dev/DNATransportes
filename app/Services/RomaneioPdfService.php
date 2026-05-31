<?php

namespace App\Services;

use App\Models\NotaFiscal;
use App\Models\Rota;
use DomainException;
use Illuminate\Support\Collection;

class RomaneioPdfService
{
    private const PAGE_WIDTH = 595;

    private const PAGE_HEIGHT = 842;

    private const MARGIN_X = 32;

    private const BOTTOM_LIMIT = 74;

    /** @var array<int, string> */
    private array $pages = [];

    private string $content = '';

    private float $y = 0;

    public function gerar(Rota $rota): string
    {
        $rota = $this->carregarDados($rota);
        $notas = $this->notasDaRota($rota);

        $this->validar($rota, $notas);
        $this->iniciarPdf();

        $this->cabecalho($rota);
        $this->secaoDadosRota($rota);
        $this->secaoVeiculoMotorista($rota);
        $this->secaoNotas($notas);
        $this->secaoAssinaturas();
        $this->finalizarPagina();

        return $this->renderizarPdf();
    }

    private function carregarDados(Rota $rota): Rota
    {
        return $rota->loadMissing([
            'origem',
            'destino',
            'motorista.usuario',
            'veiculo.modeloVeiculo',
            'pedidos.notaFiscal.remetente',
            'pedidos.notaFiscal.destinatario',
        ]);
    }

    private function validar(Rota $rota, Collection $notas): void
    {
        $faltando = [];

        if (! $rota->veiculo) {
            $faltando[] = 'veículo';
        }

        if (! $rota->motorista) {
            $faltando[] = 'motorista';
        }

        if ($notas->isEmpty()) {
            $faltando[] = 'nota fiscal vinculada';
        }

        if ($faltando !== []) {
            throw new DomainException('Não foi possível gerar o romaneio. Informação faltando: '.implode(', ', $faltando).'.');
        }
    }

    private function iniciarPdf(): void
    {
        $this->pages = [];
        $this->content = '';
        $this->y = 0;
        $this->novaPagina();
    }

    private function novaPagina(): void
    {
        if ($this->content !== '') {
            $this->finalizarPagina();
        }

        $this->content = '';
        $this->y = 802;
        $this->rodape();
    }

    private function finalizarPagina(): void
    {
        if ($this->content !== '') {
            $this->pages[] = $this->content;
            $this->content = '';
        }
    }

    private function cabecalho(Rota $rota): void
    {
        $this->filledRect(self::MARGIN_X, 768, 72, 40, 1.0, 0.76, 0.03);
        $this->text('DNA', 44, 781, 20, true, 0.06, 0.07, 0.08);
        $this->text('TRANSPORTES', 113, 795, 16, true, 1.0, 0.76, 0.03);
        $this->text('Romaneio de Carga', 113, 777, 18, true, 1, 1, 1);
        $this->text('Rota #'.$rota->id_rotas.' · Gerado em '.now()->format('d/m/Y H:i'), 113, 762, 9, false, 0.78, 0.8, 0.84);
        $this->line(self::MARGIN_X, 748, self::PAGE_WIDTH - self::MARGIN_X, 748, 1.0, 0.76, 0.03, 1.2);
        $this->y = 728;
    }

    private function secaoDadosRota(Rota $rota): void
    {
        $this->sectionTitle('Dados da rota');

        $this->infoGrid([
            ['Código/ID', '#'.$rota->id_rotas],
            ['Status', (string) ($rota->status ?? 'Não informado')],
            ['Origem', $this->centro($rota->origem)],
            ['Destino', $this->centro($rota->destino)],
            ['Data de criação', $this->data($rota->data_criacao ?? $rota->created_at)],
            ['Previsão', $this->data($rota->previsao)],
            ['Saída', $this->data($rota->data_inicio ?? $rota->data_rota)],
            ['Tipo', ucfirst((string) $rota->tipo)],
        ]);
    }

    private function secaoVeiculoMotorista(Rota $rota): void
    {
        $modelo = $rota->veiculo?->modeloVeiculo;

        $this->sectionTitle('Veículo');
        $this->infoGrid([
            ['Placa', (string) ($rota->veiculo?->placa ?? 'Não informado')],
            ['Modelo', trim(($modelo?->marca ? $modelo->marca.' ' : '').($modelo?->modelo ?? '')) ?: 'Não informado'],
            ['Tipo', (string) ($modelo?->categoria ?? 'Não informado')],
            ['Capacidade', $rota->veiculo?->capacidade_kg ? number_format((float) $rota->veiculo->capacidade_kg, 0, ',', '.').' kg' : 'Não informado'],
        ]);

        $this->sectionTitle('Motorista');
        $this->infoGrid([
            ['Nome', (string) ($rota->motorista?->usuario?->nome ?? 'Não informado')],
            ['CPF', (string) ($rota->motorista?->usuario?->cpf ?? 'Não informado')],
            ['CNH', (string) ($rota->motorista?->cnh ?? 'Não informado')],
            ['Categoria da CNH', (string) ($rota->motorista?->categoria ?? 'Não informado')],
        ]);
    }

    private function secaoNotas(Collection $notas): void
    {
        $this->sectionTitle('Notas fiscais da rota');

        $this->table(
            ['NFe', 'Série', 'Emissão', 'Remetente', 'Destinatário', 'Peso', 'Vol.', 'Valor'],
            $notas->map(fn (NotaFiscal $nota) => [
                (string) ($nota->numero_nfe ?? '--'),
                (string) ($nota->serie ?? '--'),
                $this->data($nota->emissao, 'd/m/Y'),
                (string) ($nota->remetente?->nome ?? '--'),
                (string) ($nota->destinatario?->nome ?? '--'),
                $nota->peso ? number_format((float) $nota->peso, 2, ',', '.').' kg' : '--',
                (string) ($nota->quantidade_volumes ?? '--'),
                'R$ '.number_format((float) ($nota->valor_total ?? 0), 2, ',', '.'),
            ])->all(),
            [50, 34, 58, 108, 108, 54, 34, 85],
            7.2
        );

        $this->y -= 10;
        $this->table(
            ['Chave de acesso'],
            $notas->map(fn (NotaFiscal $nota) => [(string) ($nota->chave_acesso ?? '--')])->all(),
            [self::PAGE_WIDTH - (self::MARGIN_X * 2)],
            8
        );
    }

    private function secaoAssinaturas(): void
    {
        if ($this->y < 190) {
            $this->novaPagina();
            $this->cabecalhoAssinatura();
        }

        $this->sectionTitle('Assinaturas');
        $linhaY = $this->y - 50;

        $this->line(48, $linhaY, 255, $linhaY, 0.2, 0.22, 0.25, 0.8);
        $this->line(340, $linhaY, 547, $linhaY, 0.2, 0.22, 0.25, 0.8);
        $this->text('Motorista', 110, $linhaY - 16, 9, true, 0.2, 0.22, 0.25);
        $this->text('Responsável pela conferência', 374, $linhaY - 16, 9, true, 0.2, 0.22, 0.25);

        $this->text('Declaro que recebi/conferi os volumes e documentos listados neste romaneio.', 48, $linhaY - 42, 8.5, false, 0.35, 0.37, 0.4);
        $this->y = $linhaY - 62;
    }

    private function cabecalhoAssinatura(): void
    {
        $this->text('DNATransportes', self::MARGIN_X, 802, 16, true, 1.0, 0.76, 0.03);
        $this->text('Romaneio de Carga · Continuação', self::MARGIN_X, 784, 10, false, 0.35, 0.37, 0.4);
        $this->line(self::MARGIN_X, 770, self::PAGE_WIDTH - self::MARGIN_X, 770, 1.0, 0.76, 0.03, 1);
        $this->y = 740;
    }

    private function sectionTitle(string $title): void
    {
        $this->ensureSpace(36);
        $this->filledRect(self::MARGIN_X, $this->y - 18, self::PAGE_WIDTH - (self::MARGIN_X * 2), 24, 0.08, 0.1, 0.13);
        $this->text($title, self::MARGIN_X + 10, $this->y - 10, 11, true, 1.0, 0.76, 0.03);
        $this->y -= 36;
    }

    /**
     * @param  array<int, array{0: string, 1: string}>  $items
     */
    private function infoGrid(array $items): void
    {
        $left = self::MARGIN_X;
        $colWidth = (self::PAGE_WIDTH - (self::MARGIN_X * 2) - 12) / 2;

        foreach (array_chunk($items, 2) as $row) {
            $this->ensureSpace(42);
            $rowTop = $this->y;

            foreach ($row as $index => [$label, $value]) {
                $x = $left + ($index * ($colWidth + 12));
                $this->rect($x, $rowTop - 33, $colWidth, 36, 0.78, 0.8, 0.84);
                $this->text($label, $x + 8, $rowTop - 10, 7.5, true, 0.45, 0.47, 0.5);
                $this->text($this->fit($value, 42), $x + 8, $rowTop - 25, 9, true, 0.06, 0.07, 0.08);
            }

            $this->y -= 44;
        }

        $this->y -= 4;
    }

    /**
     * @param  array<int, string>  $headers
     * @param  array<int, array<int, string>>  $rows
     * @param  array<int, float>  $widths
     */
    private function table(array $headers, array $rows, array $widths, float $fontSize): void
    {
        $this->drawTableHeader($headers, $widths, $fontSize);

        foreach ($rows as $index => $row) {
            $wrapped = [];
            $maxLines = 1;

            foreach ($row as $cellIndex => $cell) {
                $lines = $this->wrap($cell, $widths[$cellIndex] - 8, $fontSize);
                $wrapped[] = $lines;
                $maxLines = max($maxLines, count($lines));
            }

            $rowHeight = max(24, ($maxLines * ($fontSize + 3)) + 10);
            if ($this->y - $rowHeight < self::BOTTOM_LIMIT) {
                $this->novaPagina();
                $this->cabecalhoAssinatura();
                $this->drawTableHeader($headers, $widths, $fontSize);
            }

            $x = self::MARGIN_X;
            $top = $this->y;
            $fill = $index % 2 === 0 ? 0.96 : 1.0;
            $this->filledRect($x, $top - $rowHeight, array_sum($widths), $rowHeight, $fill, $fill, $fill);

            foreach ($wrapped as $cellIndex => $lines) {
                $width = $widths[$cellIndex];
                $this->rect($x, $top - $rowHeight, $width, $rowHeight, 0.82, 0.84, 0.86);

                foreach ($lines as $lineIndex => $line) {
                    $this->text($line, $x + 4, $top - 12 - ($lineIndex * ($fontSize + 3)), $fontSize, false, 0.08, 0.09, 0.1);
                }

                $x += $width;
            }

            $this->y -= $rowHeight;
        }
    }

    /**
     * @param  array<int, string>  $headers
     * @param  array<int, float>  $widths
     */
    private function drawTableHeader(array $headers, array $widths, float $fontSize): void
    {
        $this->ensureSpace(34);
        $height = 24;
        $x = self::MARGIN_X;
        $top = $this->y;

        foreach ($headers as $index => $header) {
            $width = $widths[$index];
            $this->filledRect($x, $top - $height, $width, $height, 1.0, 0.76, 0.03);
            $this->rect($x, $top - $height, $width, $height, 0.68, 0.5, 0.02);
            $this->text($this->fit($header, 18), $x + 4, $top - 15, $fontSize, true, 0.06, 0.07, 0.08);
            $x += $width;
        }

        $this->y -= $height;
    }

    private function rodape(): void
    {
        $this->line(self::MARGIN_X, 42, self::PAGE_WIDTH - self::MARGIN_X, 42, 0.82, 0.84, 0.86, 0.6);
        $this->text('DNATransportes · Documento operacional gerado pelo sistema', self::MARGIN_X, 27, 7.5, false, 0.45, 0.47, 0.5);
    }

    private function ensureSpace(float $height): void
    {
        if ($this->y - $height < self::BOTTOM_LIMIT) {
            $this->novaPagina();
            $this->cabecalhoAssinatura();
        }
    }

    private function text(string $text, float $x, float $y, float $size = 10, bool $bold = false, float $r = 0, float $g = 0, float $b = 0): void
    {
        $font = $bold ? 'F2' : 'F1';
        $this->content .= sprintf("%.3f %.3f %.3f rg\nBT /%s %.2f Tf 1 0 0 1 %.2f %.2f Tm <%s> Tj ET\n", $r, $g, $b, $font, $size, $x, $y, $this->hex($text));
    }

    private function line(float $x1, float $y1, float $x2, float $y2, float $r = 0, float $g = 0, float $b = 0, float $width = 0.5): void
    {
        $this->content .= sprintf("%.3f %.3f %.3f RG %.2f w %.2f %.2f m %.2f %.2f l S\n", $r, $g, $b, $width, $x1, $y1, $x2, $y2);
    }

    private function rect(float $x, float $y, float $width, float $height, float $r = 0, float $g = 0, float $b = 0): void
    {
        $this->content .= sprintf("%.3f %.3f %.3f RG 0.5 w %.2f %.2f %.2f %.2f re S\n", $r, $g, $b, $x, $y, $width, $height);
    }

    private function filledRect(float $x, float $y, float $width, float $height, float $r = 0, float $g = 0, float $b = 0): void
    {
        $this->content .= sprintf("%.3f %.3f %.3f rg %.2f %.2f %.2f %.2f re f\n", $r, $g, $b, $x, $y, $width, $height);
    }

    private function hex(string $text): string
    {
        $encoded = @iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $text);

        if ($encoded === false) {
            $encoded = preg_replace('/[^\x20-\x7E]/', '', $text) ?? '';
        }

        return strtoupper(bin2hex($encoded));
    }

    /**
     * @return array<int, string>
     */
    private function wrap(string $text, float $width, float $fontSize): array
    {
        $maxChars = max(8, (int) floor($width / ($fontSize * 0.48)));
        $text = trim(preg_replace('/\s+/', ' ', $text) ?? '');

        if ($text === '') {
            return ['--'];
        }

        $lines = [];
        foreach (explode("\n", wordwrap($text, $maxChars, "\n", true)) as $line) {
            $lines[] = trim($line);
        }

        return $lines ?: ['--'];
    }

    private function fit(string $text, int $max): string
    {
        $text = trim($text);

        if (mb_strlen($text) <= $max) {
            return $text;
        }

        return rtrim(mb_substr($text, 0, max(1, $max - 3))).'...';
    }

    private function data($date, string $format = 'd/m/Y H:i'): string
    {
        if (! $date) {
            return 'Não informado';
        }

        if ($date instanceof \DateTimeInterface) {
            return $date->format($format);
        }

        return (string) $date;
    }

    private function centro($centro): string
    {
        if (! $centro) {
            return 'Não informado';
        }

        return trim(sprintf(
            '%s · %s/%s',
            $centro->nome ?? 'Centro',
            $centro->cidade ?? '--',
            $centro->uf ?? '--'
        ));
    }

    private function notasDaRota(Rota $rota): Collection
    {
        return $rota->pedidos
            ->map(fn ($pedido) => $pedido->notaFiscal)
            ->filter()
            ->unique('id_notaFiscal')
            ->values();
    }

    private function renderizarPdf(): string
    {
        $objects = [
            1 => '<< /Type /Catalog /Pages 2 0 R >>',
            3 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>',
            4 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>',
        ];

        $pageObjectIds = [];
        $nextObjectId = 5;

        foreach ($this->pages as $pageContent) {
            $pageId = $nextObjectId++;
            $contentId = $nextObjectId++;
            $pageObjectIds[] = $pageId.' 0 R';

            $objects[$pageId] = sprintf(
                '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 %d %d] /Resources << /Font << /F1 3 0 R /F2 4 0 R >> >> /Contents %d 0 R >>',
                self::PAGE_WIDTH,
                self::PAGE_HEIGHT,
                $contentId
            );
            $objects[$contentId] = '<< /Length '.strlen($pageContent)." >>\nstream\n".$pageContent."\nendstream";
        }

        $objects[2] = '<< /Type /Pages /Kids ['.implode(' ', $pageObjectIds).'] /Count '.count($pageObjectIds).' >>';
        ksort($objects);

        $pdf = "%PDF-1.4\n%âãÏÓ\n";
        $offsets = [0 => 0];

        foreach ($objects as $id => $object) {
            $offsets[$id] = strlen($pdf);
            $pdf .= $id." 0 obj\n".$object."\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objects) + 1)."\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        $pdf .= "trailer\n<< /Size ".(count($objects) + 1)." /Root 1 0 R >>\nstartxref\n".$xrefOffset."\n%%EOF";

        return $pdf;
    }
}
