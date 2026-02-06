<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductionService;

class DashboardController extends Controller
{
    protected $productionService;

    public function __construct(ProductionService $productionService)
    {
        $this->productionService = $productionService;
    }

    public function index(Request $request)
    {
        // Extrai filtros do request no padrão filter[campo]
        $filters = $this->extractFilters($request);

        // Busca dados através do service
        $data = $this->productionService->getDashboardData($filters);

        // Lista de linhas para o filtro
        $productionLines = $this->productionService->getProductionLinesForFilter();

        return view('dashboard', [
            'productionData' => $data['production_lines'],
            'consolidated' => $data['consolidated'],
            'period' => $data['period'],
            'productionLines' => $productionLines,
            'filters' => $filters,
            'activeFilters' => $data['active_filters'],
        ]);
    }

    /**
     * Extrai e valida filtros do request
     * 
     * @param Request $request
     * @return array
     */
    private function extractFilters(Request $request): array
    {
        // Pega todos os filtros que vêm no formato filter[campo]
        $rawFilters = $request->input('filter', []);

        $filters = [];

        // Filtro de linha de produção
        if (!empty($rawFilters['production_line_id'])) {
            $filters['production_line_id'] = (int) $rawFilters['production_line_id'];
        }

        // Filtro de data inicial
        if (!empty($rawFilters['start_date'])) {
            $filters['start_date'] = $rawFilters['start_date'];
        }

        // Filtro de data final
        if (!empty($rawFilters['end_date'])) {
            $filters['end_date'] = $rawFilters['end_date'];
        }

        // Filtro de eficiência mínima
        if (!empty($rawFilters['min_efficiency'])) {
            $filters['min_efficiency'] = (float) $rawFilters['min_efficiency'];
        }

        return $filters;
    }
}
