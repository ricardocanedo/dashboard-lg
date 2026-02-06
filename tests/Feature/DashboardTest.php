<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Model\Plant;
use App\Model\ProductionLine;
use App\Model\ProductionRecord;

class DashboardTest extends TestCase
{
    /** @test */
    public function it_displays_dashboard_page()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_shows_consolidated_metrics()
    {
        $plant = factory(Plant::class)->create();
        $line = factory(ProductionLine::class)->create(['plant_id' => $plant->id]);

        factory(ProductionRecord::class)->create([
            'production_line_id' => $line->id,
            'good_parts' => 1000,
            'defective_parts' => 50
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Total Produzido');
        $response->assertSee('Peças Boas');
        $response->assertSee('Peças Defeituosas');
        $response->assertSee('Eficiência Média');
    }

    /** @test */
    public function it_shows_production_table()
    {
        $plant = factory(Plant::class)->create();
        $line = factory(ProductionLine::class)->create([
            'plant_id' => $plant->id,
            'name' => 'Geladeira'
        ]);

        factory(ProductionRecord::class)->create([
            'production_line_id' => $line->id
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Geladeira');
        $response->assertSee('Detalhamento por Linha de Produção');
    }

    /** @test */
    public function it_filters_by_production_line()
    {
        $plant = factory(Plant::class)->create();
        $line1 = factory(ProductionLine::class)->create([
            'plant_id' => $plant->id,
            'name' => 'Geladeira'
        ]);
        $line2 = factory(ProductionLine::class)->create([
            'plant_id' => $plant->id,
            'name' => 'TV'
        ]);

        factory(ProductionRecord::class)->create(['production_line_id' => $line1->id]);
        factory(ProductionRecord::class)->create(['production_line_id' => $line2->id]);

        $response = $this->get('/?filter[production_line_id]=' . $line1->id);

        $response->assertStatus(200);
        $response->assertSee('Geladeira');
    }

    /** @test */
    public function it_filters_by_date_range()
    {
        $plant = factory(Plant::class)->create();
        $line = factory(ProductionLine::class)->create(['plant_id' => $plant->id]);

        factory(ProductionRecord::class)->create([
            'production_line_id' => $line->id,
            'production_date' => '2026-01-15'
        ]);

        $response = $this->get('/?filter[start_date]=2026-01-01&filter[end_date]=2026-01-31');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_accepts_filter_parameters()
    {
        $plant = factory(Plant::class)->create();
        $line = factory(ProductionLine::class)->create([
            'plant_id' => $plant->id,
            'name' => 'Geladeira'
        ]);

        factory(ProductionRecord::class)->create([
            'production_line_id' => $line->id,
            'efficiency' => 95.0
        ]);

        $response = $this->get('/?filter[production_line_id]=' . $line->id . '&filter[min_efficiency]=90');

        $response->assertStatus(200);
        $response->assertSee('Geladeira');
    }
}
