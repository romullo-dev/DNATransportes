<?php

namespace App\Services;

use App\DTOs\ClienteNfeData;
use App\DTOs\EnderecoNfeData;
use App\DTOs\ImportacaoNfeData;
use App\DTOs\NotaFiscalNfeData;
use App\DTOs\ProdutoNfeData;
use App\Models\Pedido;
use App\Repositories\ClienteRepository;
use App\Repositories\EnderecoRepository;
use App\Repositories\NotaFiscalRepository;
use App\Repositories\PedidoRepository;
use App\Repositories\ProdutoRepository;
use Carbon\CarbonImmutable;
use DomainException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use SimpleXMLElement;

class ImportacaoNfeService
{
    public function __construct(
        private readonly ClienteRepository $clientes,
        private readonly EnderecoRepository $enderecos,
        private readonly NotaFiscalRepository $notasFiscais,
        private readonly ProdutoRepository $produtos,
        private readonly PedidoRepository $pedidos,
    ) {}

    public function importar(UploadedFile $arquivo): Pedido
    {
        $dados = $this->lerXml($arquivo);

        return DB::transaction(function () use ($dados) {
            $emitente = $this->clientes->firstOrCreateFromNfe($dados->emitente);
            $destinatario = $this->clientes->firstOrCreateFromNfe($dados->destinatario);
            $enderecoEmitente = $this->enderecos->firstOrCreateFromNfe($dados->enderecoEmitente);
            $enderecoDestinatario = $this->enderecos->firstOrCreateFromNfe($dados->enderecoDestinatario);

            $this->produtos->firstOrCreateMany($dados->produtos);

            $notaFiscal = $this->notasFiscais->firstOrCreateFromNfe(
                data: $dados->notaFiscal,
                remetente: $emitente,
                destinatario: $destinatario,
                enderecoRemetente: $enderecoEmitente,
                enderecoDestinatario: $enderecoDestinatario,
            );

            return $this->pedidos->firstOrCreateFromNotaFiscal($notaFiscal);
        });
    }

    private function lerXml(UploadedFile $arquivo): ImportacaoNfeData
    {
        $xml = simplexml_load_file($arquivo->getRealPath());

        if (! $xml instanceof SimpleXMLElement) {
            throw new DomainException('XML inválido ou ilegível.');
        }

        $infNFe = $this->infNFe($xml);
        $ide = $infNFe->ide ?? null;

        if (! $ide) {
            throw new DomainException('Erro: tag <ide> não encontrada no XML.');
        }

        return new ImportacaoNfeData(
            emitente: $this->cliente($infNFe->emit, 'emitente'),
            enderecoEmitente: $this->endereco($infNFe->emit->enderEmit),
            destinatario: $this->cliente($infNFe->dest, 'destinatário'),
            enderecoDestinatario: $this->endereco($infNFe->dest->enderDest),
            notaFiscal: $this->notaFiscal($xml, $infNFe),
            produtos: $this->produtosDoXml($infNFe),
        );
    }

    private function infNFe(SimpleXMLElement $xml): SimpleXMLElement
    {
        $namespaces = $xml->getNamespaces(true);

        if (isset($namespaces[''])) {
            $xml->registerXPathNamespace('nfe', $namespaces['']);
            $resultado = $xml->xpath('//nfe:infNFe');
        } else {
            $resultado = $xml->xpath('//infNFe');
        }

        $infNFe = $resultado[0] ?? null;

        if (! $infNFe instanceof SimpleXMLElement) {
            throw new DomainException('Erro: tag <infNFe> não encontrada no XML.');
        }

        return $infNFe;
    }

    private function cliente(SimpleXMLElement $node, string $tipo): ClienteNfeData
    {
        $documento = (string) ($node->CNPJ ?: $node->CPF);

        if ($documento === '') {
            throw new DomainException("Documento do cliente {$tipo} não encontrado na NFe.");
        }

        return new ClienteNfeData(
            nome: (string) $node->xNome,
            documento: $documento,
            tipo: $tipo,
        );
    }

    private function endereco(SimpleXMLElement $node): EnderecoNfeData
    {
        return new EnderecoNfeData(
            cep: (string) $node->CEP,
            logradouro: (string) $node->xLgr,
            numero: $this->nullableString($node->nro ?? null),
            observacao: $this->nullableString($node->xCpl ?? null),
            uf: (string) $node->UF,
            bairro: $this->nullableString($node->xBairro ?? null),
            cidade: (string) $node->xMun,
        );
    }

    private function notaFiscal(SimpleXMLElement $xml, SimpleXMLElement $infNFe): NotaFiscalNfeData
    {
        $ide = $infNFe->ide;
        $chaveAcesso = (string) ($xml->protNFe->infProt->chNFe ?? '');

        if ($chaveAcesso === '') {
            $chaveAcesso = str_replace('NFe', '', (string) ($infNFe['Id'] ?? ''));
        }

        if ($chaveAcesso === '') {
            throw new DomainException('Chave de acesso da NFe não encontrada.');
        }

        return new NotaFiscalNfeData(
            chaveAcesso: $chaveAcesso,
            numero: (int) $ide->nNF,
            serie: $this->nullableInt($ide->serie ?? null),
            emissao: CarbonImmutable::parse((string) $ide->dhEmi),
            valorTotal: (float) ($infNFe->total->ICMSTot->vNF ?? 0),
            peso: $this->nullableFloat($infNFe->transp->vol->pesoB ?? null),
            quantidadeVolumes: (int) ($infNFe->transp->vol->qVol ?? 0),
        );
    }

    /**
     * @return array<int, ProdutoNfeData>
     */
    private function produtosDoXml(SimpleXMLElement $infNFe): array
    {
        $produtos = [];

        foreach ($infNFe->det as $det) {
            $nome = trim((string) ($det->prod->xProd ?? ''));

            if ($nome === '') {
                continue;
            }

            $produtos[] = new ProdutoNfeData(
                nome: $nome,
                codigo: $this->nullableString($det->prod->cProd ?? null),
            );
        }

        return $produtos;
    }

    private function nullableString(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value !== '' ? $value : null;
    }

    private function nullableInt(mixed $value): ?int
    {
        $value = trim((string) $value);

        return $value !== '' ? (int) $value : null;
    }

    private function nullableFloat(mixed $value): ?float
    {
        $value = trim((string) $value);

        return $value !== '' ? (float) $value : null;
    }
}
