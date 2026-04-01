<?php

namespace App\View\Components;

use App\Models\AdminGroup;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Illuminate\View\Component;

class BtnEdit extends Component
{
    /**
     * Create a new component instance.
     */
    public $id;
    public $route;
    public $allowed_permissions;
    public function __construct($id = "", $route = "")
    {
        $this->id = $id;
        $this->route = $route;
        $this->allowed_permissions = unserialize(AdminGroup::find(Crypt::decrypt(Session::get("group_id")))->permissions);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.btn-edit');
    }
}
