<?php

namespace App\View\Components;

use App\Models\HospitGroup;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Illuminate\View\Component;

class ModuleCheck extends Component
{
    /**
     * Create a new component instance.
     */
    public $allowed;
    public function __construct($route)
    {
        $this->allowed = in_array($route,unserialize(HospitGroup::find(Crypt::decrypt(Session::get("group_id")))->permissions));
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.module-check');
    }
}
