<?php

namespace App\View\Components;

use App\Models\AdminGroup;
use App\Models\AdminPermission;
use App\Models\AdminUser;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Illuminate\View\Component;

class Header extends Component
{
    /**
     * Create a new component instance.
     */
    public $user;
    public $all_permissions;
    public $allowed_permissions;
    public $heading;
    public function __construct($heading = null)
    {
        // echo Crypt::decrypt(Session::get("user_id"));
        // exit;
        $this->user = AdminUser::find(Crypt::decrypt(Session::get("user_id")));
        $this->all_permissions = AdminPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"R","parent_id"=>0])->get();
        $this->allowed_permissions = unserialize(AdminGroup::find(get_logged_in_user()->admin_group_id)->permissions);
        $this->heading = $heading ?? "Dashboard";
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.header');
    }
}
