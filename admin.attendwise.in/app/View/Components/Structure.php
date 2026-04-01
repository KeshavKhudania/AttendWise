<?php

namespace App\View\Components;

use App\Models\Hospital;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Illuminate\View\Component;

class Structure extends Component
{
    /**
     * Create a new component instance.
     */
    public $hospital;
    public $title;
    public function __construct($title = null)
    {
        $this->hospital = Session::has("hospital_id") === true ? Hospital::find(Crypt::decrypt(Session::get("hospital_id"))):["name"=>"AttendWise"];
        $this->title = Session::has("hospital_id") === true ? Hospital::find(Crypt::decrypt(Session::get("hospital_id"))):["name"=>"AttendWise"];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.structure');
    }
}
