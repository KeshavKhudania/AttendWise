<?php

namespace App\View\Components;

use Closure;
use App\Models\AdminGroup;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class BtnAdd extends Component
{
    /**
     * Create a new component instance.
     */
    public $route;
    public $allowed_permissions;
    public function __construct($route = "")
    {
        $this->route = $route;
        $this->allowed_permissions = unserialize(AdminGroup::find(Crypt::decrypt(Session::get("group_id")))->permissions);
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.btn-add');
    }
}
