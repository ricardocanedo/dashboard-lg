<?php

namespace App\Services;

use App\Model\ProductionLine;
use App\Model\ProductionRecord;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductionService
{
    /**
     * Busca dados de produção para o dashboard
     * 
     * @param int|null $productionLineId ID da linha de produção (null = todas)
     * @return array
     */
    public function getDashboardData(?int $productionLineId = null): array
    {
        // Período: Janeiro/2026
        $startDate = Carbon::create(2026, 1, 1)->startOfMonth();
        $endDate = Carbon::create(2026, 1, 31)->endOfMonth();

        // Query base
        $query = ProductionRecord::query()
            ->join('production_lines', 'production_records.production_line_id', '=', 'production_lines.id')
            ->whereBetween('production_records.production_date', [$startDate, $endDate])
            ->select(
                'production_lines.id as line_id',
                'production_lines.name as line_name',
                DB::raw('SUM(production_records.good_parts) as total_good_parts'),
                DB::raw('SUM(production_records.defective_parts) as total_defective_parts'),
                DB::raw('SUM(production_records.good_parts + production_records.defective_parts) as total_produced'),
                DB::raw('ROUND(AVG(production_records.efficiency), 2) as avg_efficiency')
            )
            ->groupBy('production_lines.id', 'production_lines.name')
            ->orderBy('production_lines.name');

        // Aplica filtro por linha se fornecido
        if ($productionLineId) {
            $query->where('production_lines.id', $productionLineId);
        }

        // Busca todos os resultados
        $productionLines = $query->get();

        // Calcula consolidado geral
        $consolidated = $this->getConsolidatedData($startDate, $endDate, $productionLineId);

        return [
            'production_lines' => $productionLines,
            'consolidated' => $consolidated,
            'period' => [
                'start' => $startDate->format('d/m/Y'),
                'end' => $endDate->format('d/m/Y'),
                'month' => $startDate->format('F/Y'),
            ],
        ];
    }

    /**
     * Calcula dados consolidados (soma de todas as linhas)
     * 
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param int|null $productionLineId
     * @return array
     */
    private function getConsolidatedData(Carbon $startDate, Carbon $endDate, ?int $productionLineId = null): array
    {
        $query = ProductionRecord::query()
            ->whereBetween('production_date', [$startDate, $endDate]);

        if ($productionLineId) {
            $query->where('production_line_id', $productionLineId);
        }

        $totals = $query->select(
            DB::raw('SUM(good_parts) as total_good_parts'),
            DB::raw('SUM(defective_parts) as total_defective_parts'),
            DB::raw('SUM(good_parts + defective_parts) as total_produced')
        )->first();

        $totalProduced = $totals->total_produced ?? 0;
        $totalGoodParts = $totals->total_good_parts ?? 0;

        // Calcula eficiência consolidada
        $efficiency = $totalProduced > 0
            ? round(($totalGoodParts / $totalProduced) * 100, 2)
            : 0;

        return [
            'total_good_parts' => $totalGoodParts,
            'total_defective_parts' => $totals->total_defective_parts ?? 0,
            'total_produced' => $totalProduced,
            'efficiency' => $efficiency,
        ];
    }

    /**
     * Retorna lista de linhas de produção para o filtro
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProductionLinesForFilter()
    {
        return ProductionLine::orderBy('name')->get(['id', 'name']);
    }
}
