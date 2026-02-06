<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProductionChart extends Component
{
    public $productionData;
    public $chartId;

    /**
     * Create a new component instance.
     *
     * @param \Illuminate\Support\Collection $productionData
     * @param string $chartId
     * @return void
     */
    public function __construct($productionData, $chartId = 'efficiencyChart')
    {
        $this->productionData = $productionData;
        $this->chartId = $chartId;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.production-chart');
    }
}
