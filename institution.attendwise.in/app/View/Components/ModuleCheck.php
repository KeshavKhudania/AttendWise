<?php

namespace App\View\Components;

use App\Models\AdminGroup;
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
    {   $allowed_permissions = unserialize(AdminGroup::find(Crypt::decrypt(Session::get("group_id")))->permissions);
        $this->allowed = in_array($route,$allowed_permissions);
        if (!$this->allowed) {
            foreach ($allowed_permissions as $permission) {
                    if (str_ends_with($permission, '.manage') && str_starts_with($route, $permission . '.')) {
                        $this->allowed = true;
                        break;
                    }
                }
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.module-check');
    }
}
