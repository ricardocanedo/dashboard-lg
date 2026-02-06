<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Model\Plant;
use App\Model\ProductionLine;
use App\Model\ProductionRecord;

class ProductionLineTest extends TestCase
{
    /** @test */
    public function it_can_create_a_production_line()
    {
        $plant = factory(Plant::class)->create();

        $line = factory(ProductionLine::class)->create([
            'name' => 'Geladeira',
            'plant_id' => $plant->id
        ]);

        $this->assertDatabaseHas('production_lines', [
            'name' => 'Geladeira',
            'plant_id' => $plant->id
        ]);
    }

    /** @test */
    public function it_belongs_to_a_plant()
    {
        $plant = factory(Plant::class)->create();
        $line = factory(ProductionLine::class)->create(['plant_id' => $plant->id]);

        $this->assertInstanceOf(Plant::class, $line->plant);
        $this->assertEquals($plant->id, $line->plant->id);
    }

    /** @test */
    public function it_has_production_records_relationship()
    {
        $line = factory(ProductionLine::class)->create();
        factory(ProductionRecord::class, 5)->create(['production_line_id' => $line->id]);

        $this->assertCount(5, $line->productionRecords);
        $this->assertInstanceOf(ProductionRecord::class, $line->productionRecords->first());
    }
}
