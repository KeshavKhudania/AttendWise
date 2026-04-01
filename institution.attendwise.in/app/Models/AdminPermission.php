<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminPermission extends Model
{
    use HasFactory;
    protected $table = 'institution_admin_permissions';
    protected bool $applyInstitutionScope = false;
    public function children()
    
{
    return $this->hasMany(AdminPermission::class, 'parent_id', 'id')
                ->where('deleted_at', null)
                ->where('status', 1);
}
}
