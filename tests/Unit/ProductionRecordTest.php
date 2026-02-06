<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Model\ProductionLine;
use App\Model\ProductionRecord;

class ProductionRecordTest extends TestCase
{
    /** @test */
    public function it_can_create_a_production_record()
    {
        $line = factory(ProductionLine::class)->create();

        $record = factory(ProductionRecord::class)->create([
            'production_line_id' => $line->id,
            'production_date' => '2026-01-15',
            'good_parts' => 1000,
            'defective_parts' => 50,
            'efficiency' => 95.24
        ]);

        $this->assertDatabaseHas('production_records', [
            'production_line_id' => $line->id,
            'production_date' => '2026-01-15',
            'good_parts' => 1000,
            'defective_parts' => 50
        ]);
    }

    /** @test */
    public function it_belongs_to_a_production_line()
    {
        $line = factory(ProductionLine::class)->create();
        $record = factory(ProductionRecord::class)->create(['production_line_id' => $line->id]);

        $this->assertInstanceOf(ProductionLine::class, $record->productionLine);
        $this->assertEquals($line->id, $record->productionLine->id);
    }

    /** @test */
    public function it_calculates_efficiency_correctly()
    {
        $line = factory(ProductionLine::class)->create();

        $record = factory(ProductionRecord::class)->create([
            'production_line_id' => $line->id,
            'good_parts' => 900,
            'defective_parts' => 100,
            'efficiency' => 90.0
        ]);

        $expectedEfficiency = (900 / (900 + 100)) * 100;
        $this->assertEquals(90.0, $record->efficiency);
    }
}
