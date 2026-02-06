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
        // Pega o filtro de linha de produção (se houver)
        $productionLineId = $request->input('production_line_id');

        // Busca dados através do service
        $data = $this->productionService->getDashboardData($productionLineId);

        // Lista de linhas para o filtro
        $productionLines = $this->productionService->getProductionLinesForFilter();

        return view('dashboard', [
            'productionData' => $data['production_lines'],
            'consolidated' => $data['consolidated'],
            'period' => $data['period'],
            'productionLines' => $productionLines,
            'selectedLine' => $productionLineId,
        ]);
    }
}
