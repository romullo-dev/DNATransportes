<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Analytics\AnalyticsFilterRequest;
use App\Services\AnalyticsService;
use Illuminate\Http\JsonResponse;

class AnalyticsController extends Controller
{
    public function resumo(AnalyticsFilterRequest $request, AnalyticsService $analytics): JsonResponse
    {
        return response()->json($analytics->resumo($request->validated()));
    }

    public function pedidos(AnalyticsFilterRequest $request, AnalyticsService $analytics): JsonResponse
    {
        return response()->json($analytics->pedidos($request->validated()));
    }

    public function rotas(AnalyticsFilterRequest $request, AnalyticsService $analytics): JsonResponse
    {
        return response()->json($analytics->rotas($request->validated()));
    }

    public function motoristas(AnalyticsFilterRequest $request, AnalyticsService $analytics): JsonResponse
    {
        return response()->json($analytics->motoristas($request->validated()));
    }

    public function clientes(AnalyticsFilterRequest $request, AnalyticsService $analytics): JsonResponse
    {
        return response()->json($analytics->clientes($request->validated()));
    }

    public function ocorrencias(AnalyticsFilterRequest $request, AnalyticsService $analytics): JsonResponse
    {
        return response()->json($analytics->ocorrencias($request->validated()));
    }

    public function sla(AnalyticsFilterRequest $request, AnalyticsService $analytics): JsonResponse
    {
        return response()->json($analytics->sla($request->validated()));
    }

    public function faturamento(AnalyticsFilterRequest $request, AnalyticsService $analytics): JsonResponse
    {
        return response()->json($analytics->faturamento($request->validated()));
    }

    public function filiais(AnalyticsFilterRequest $request, AnalyticsService $analytics): JsonResponse
    {
        return response()->json($analytics->filiais($request->validated()));
    }

    public function evolucaoMensal(AnalyticsFilterRequest $request, AnalyticsService $analytics): JsonResponse
    {
        return response()->json($analytics->evolucaoMensal($request->validated()));
    }
}
