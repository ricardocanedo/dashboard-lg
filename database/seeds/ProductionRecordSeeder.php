<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductionRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buscar todas as linhas de produção
        $productionLines = DB::table('production_lines')->get();

        // Período: Janeiro 2025 a Janeiro 2026
        $startDate = Carbon::create(2025, 1, 1);
        $endDate = Carbon::create(2026, 1, 31);

        $records = [];

        // Para cada linha de produção
        foreach ($productionLines as $line) {
            $currentDate = $startDate->copy();

            // Gerar registros para cada dia no período
            while ($currentDate->lte($endDate)) {
                // Pular finais de semana (opcional - remova se quiser incluir)
                if ($currentDate->isWeekday()) {
                    // Simular produção variável por linha
                    $goodParts = $this->getGoodPartsForLine($line->name);
                    $defectiveParts = $this->getDefectivePartsForLine($line->name);

                    // Calcular eficiência
                    $totalParts = $goodParts + $defectiveParts;
                    $efficiency = $totalParts > 0 ? round(($goodParts / $totalParts) * 100, 2) : 0;

                    $records[] = [
                        'production_line_id' => $line->id,
                        'production_date' => $currentDate->format('Y-m-d'),
                        'good_parts' => $goodParts,
                        'defective_parts' => $defectiveParts,
                        'efficiency' => $efficiency,
                        'created_at' => $currentDate->copy()->addHours(18), // Registra às 18h do dia
                        'updated_at' => $currentDate->copy()->addHours(18),
                    ];
                }

                $currentDate->addDay();
            }
        }

        // Inserir em lotes para melhor performance
        foreach (array_chunk($records, 500) as $chunk) {
            DB::table('production_records')->insert($chunk);
        }
    }

    /**
     * Gerar quantidade de peças boas baseado no tipo de linha
     */
    private function getGoodPartsForLine($lineName)
    {
        $baseProduction = [
            'Geladeira' => rand(80, 120),
            'Máquina de Lavar' => rand(100, 150),
            'TV' => rand(150, 200),
            'Ar-Condicionado' => rand(90, 130),
        ];

        return $baseProduction[$lineName] ?? rand(100, 150);
    }

    /**
     * Gerar quantidade de peças defeituosas baseado no tipo de linha
     */
    private function getDefectivePartsForLine($lineName)
    {
        $baseDefects = [
            'Geladeira' => rand(2, 8),
            'Máquina de Lavar' => rand(3, 10),
            'TV' => rand(5, 15),
            'Ar-Condicionado' => rand(3, 9),
        ];

        return $baseDefects[$lineName] ?? rand(3, 10);
    }
}
