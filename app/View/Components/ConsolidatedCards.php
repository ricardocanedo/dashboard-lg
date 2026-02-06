<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ConsolidatedCards extends Component
{
    public $consolidated;

    /**
     * Create a new component instance.
     *
     * @param array $consolidated
     * @return void
     */
    public function __construct($consolidated)
    {
        $this->consolidated = $consolidated;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.consolidated-cards');
    }
}
