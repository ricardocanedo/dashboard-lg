<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Model\Plant;
use App\Model\ProductionLine;

class PlantTest extends TestCase
{
    /** @test */
    public function it_can_create_a_plant()
    {
        $plant = factory(Plant::class)->create([
            'name' => 'Plant Test'
        ]);

        $this->assertDatabaseHas('plants', [
            'name' => 'Plant Test'
        ]);
    }

    /** @test */
    public function it_has_production_lines_relationship()
    {
        $plant = factory(Plant::class)->create();
        factory(ProductionLine::class, 3)->create(['plant_id' => $plant->id]);

        $this->assertCount(3, $plant->productionLines);
        $this->assertInstanceOf(ProductionLine::class, $plant->productionLines->first());
    }
}
