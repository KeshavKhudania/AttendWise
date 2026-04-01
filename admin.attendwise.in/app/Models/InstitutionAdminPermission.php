<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionAdminPermission extends Model
{
    use HasFactory;
    public function children()
{
    return $this->hasMany(InstitutionAdminPermission::class, 'parent_id', 'id')
                ->where('deleted_at', null)
                ->where('status', 1);
}
}
