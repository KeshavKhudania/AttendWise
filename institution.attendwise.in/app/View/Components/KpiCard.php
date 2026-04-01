<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class KpiCard extends Component
{
    /**
     * Create a new component instance.
     */
    public $color;
    public $icon;
    public $title;
    public $value;
    public $delta;
    public function __construct($color = "primary", $icon = "fas fa-chart-bar", $title = "KPI Title", $value = "0", $delta = "0%")
    {
        $this->color = $color;
        $this->icon = $icon;
        $this->title = $title;
        $this->value = $value;
        $this->delta = $delta;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.kpi-card');
    }
}
