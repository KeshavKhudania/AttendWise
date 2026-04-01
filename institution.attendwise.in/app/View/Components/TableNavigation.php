<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableNavigation extends Component
{
    /**
     * Create a new component instance.
     */
    public $users;
    public function __construct($data)
    {
        $this->users = $data;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table-navigation');
    }
}
