<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProductionTable extends Component
{
    public $productionData;

    /**
     * Create a new component instance.
     *
     * @param \Illuminate\Support\Collection $productionData
     * @return void
     */
    public function __construct($productionData)
    {
        $this->productionData = $productionData;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.production-table');
    }
}
