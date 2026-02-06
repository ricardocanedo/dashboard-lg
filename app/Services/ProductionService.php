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
     * @param array $filters Array de filtros (ex: ['production_line_id' => 1, 'start_date' => '2026-01-01'])
     * @return array
     */
    public function getDashboardData(array $filters = []): array
    {
        // Parse de datas dos filtros ou usa padrão (Janeiro/2026)
        $startDate = isset($filters['start_date'])
            ? Carbon::parse($filters['start_date'])->startOfDay()
            : Carbon::create(2026, 1, 1)->startOfMonth();

        $endDate = isset($filters['end_date'])
            ? Carbon::parse($filters['end_date'])->endOfDay()
            : Carbon::create(2026, 1, 31)->endOfMonth();

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

        // Aplica filtros
        $query = $this->applyFilters($query, $filters);

        // Busca todos os resultados
        $productionLines = $query->get();

        // Calcula consolidado geral
        $consolidated = $this->getConsolidatedData($startDate, $endDate, $filters);

        return [
            'production_lines' => $productionLines,
            'consolidated' => $consolidated,
            'period' => [
                'start' => $startDate->format('d/m/Y'),
                'end' => $endDate->format('d/m/Y'),
                'month' => $startDate->translatedFormat('F/Y'),
                'start_raw' => $startDate->format('Y-m-d'),
                'end_raw' => $endDate->format('Y-m-d'),
            ],
            'active_filters' => $this->getActiveFilters($filters),
        ];
    }

    /**
     * Aplica filtros na query
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applyFilters($query, array $filters)
    {
        // Filtro por linha de produção
        if (!empty($filters['production_line_id'])) {
            $query->where('production_lines.id', $filters['production_line_id']);
        }

        // Filtro por eficiência mínima
        if (!empty($filters['min_efficiency'])) {
            $query->havingRaw('avg_efficiency >= ?', [$filters['min_efficiency']]);
        }

        // Adicione outros filtros aqui conforme necessário
        // Ex: filtro por planta, turno, etc.

        return $query;
    }

    /**
     * Calcula dados consolidados (soma de todas as linhas)
     * 
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param array $filters
     * @return array
     */
    private function getConsolidatedData(Carbon $startDate, Carbon $endDate, array $filters = []): array
    {
        $query = ProductionRecord::query()
            ->whereBetween('production_date', [$startDate, $endDate]);

        // Aplica filtro de linha de produção se existir
        if (!empty($filters['production_line_id'])) {
            $query->where('production_line_id', $filters['production_line_id']);
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
     * Retorna filtros ativos formatados para exibição
     * 
     * @param array $filters
     * @return array
     */
    private function getActiveFilters(array $filters): array
    {
        $active = [];

        if (!empty($filters['production_line_id'])) {
            $line = ProductionLine::find($filters['production_line_id']);
            $active['production_line'] = $line ? $line->name : 'Linha não encontrada';
        }

        if (!empty($filters['start_date'])) {
            $active['start_date'] = Carbon::parse($filters['start_date'])->format('d/m/Y');
        }

        if (!empty($filters['end_date'])) {
            $active['end_date'] = Carbon::parse($filters['end_date'])->format('d/m/Y');
        }

        if (!empty($filters['min_efficiency'])) {
            $active['min_efficiency'] = $filters['min_efficiency'] . '%';
        }

        return $active;
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
