<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Model\Plant;
use App\Model\ProductionLine;
use App\Model\ProductionRecord;

class ProductionServiceTest extends TestCase
{
    /** @test */
    public function it_can_create_production_records_with_correct_efficiency()
    {
        $plant = factory(Plant::class)->create();
        $line = factory(ProductionLine::class)->create(['plant_id' => $plant->id]);

        $goodParts = 950;
        $defectiveParts = 50;
        $expectedEfficiency = round(($goodParts / ($goodParts + $defectiveParts)) * 100, 2);

        $record = ProductionRecord::create([
            'production_line_id' => $line->id,
            'production_date' => now()->format('Y-m-d'),
            'good_parts' => $goodParts,
            'defective_parts' => $defectiveParts,
            'efficiency' => $expectedEfficiency,
        ]);

        $this->assertEquals($expectedEfficiency, $record->efficiency);
        $this->assertEquals($line->id, $record->production_line_id);
        $this->assertEquals($goodParts, $record->good_parts);
        $this->assertEquals($defectiveParts, $record->defective_parts);
    }

    /** @test */
    public function it_can_filter_records_by_date()
    {
        $plant = factory(Plant::class)->create();
        $line = factory(ProductionLine::class)->create(['plant_id' => $plant->id]);

        $recordJan = factory(ProductionRecord::class)->create([
            'production_line_id' => $line->id,
            'production_date' => '2026-01-15'
        ]);

        $recordFeb = factory(ProductionRecord::class)->create([
            'production_line_id' => $line->id,
            'production_date' => '2026-02-15'
        ]);

        $janRecords = ProductionRecord::whereBetween('production_date', ['2026-01-01', '2026-01-31'])->get();

        $this->assertCount(1, $janRecords);
        $this->assertEquals('2026-01-15', $janRecords->first()->production_date);
    }

    /** @test */
    public function it_can_filter_records_by_efficiency()
    {
        $plant = factory(Plant::class)->create();
        $line1 = factory(ProductionLine::class)->create(['plant_id' => $plant->id]);
        $line2 = factory(ProductionLine::class)->create(['plant_id' => $plant->id]);

        factory(ProductionRecord::class)->create([
            'production_line_id' => $line1->id,
            'efficiency' => 98.0
        ]);

        factory(ProductionRecord::class)->create([
            'production_line_id' => $line2->id,
            'efficiency' => 85.0
        ]);

        $highEfficiencyRecords = ProductionRecord::where('efficiency', '>=', 90)->get();

        $this->assertCount(1, $highEfficiencyRecords);
        $this->assertGreaterThanOrEqual(90, $highEfficiencyRecords->first()->efficiency);
    }

    /** @test */
    public function it_can_aggregate_production_data_by_line()
    {
        $plant = factory(Plant::class)->create();
        $line = factory(ProductionLine::class)->create([
            'plant_id' => $plant->id,
            'name' => 'Geladeira'
        ]);

        $goodParts = 1000;
        $defectiveParts = 50;

        for ($i = 0; $i < 3; $i++) {
            ProductionRecord::create([
                'production_line_id' => $line->id,
                'production_date' => now()->addDays($i)->format('Y-m-d'),
                'good_parts' => $goodParts,
                'defective_parts' => $defectiveParts,
                'efficiency' => round(($goodParts / ($goodParts + $defectiveParts)) * 100, 2),
            ]);
        }

        $totalGoodParts = ProductionRecord::where('production_line_id', $line->id)
            ->sum('good_parts');

        $totalDefectiveParts = ProductionRecord::where('production_line_id', $line->id)
            ->sum('defective_parts');

        $this->assertEquals(3000, $totalGoodParts);
        $this->assertEquals(150, $totalDefectiveParts);
    }
}
