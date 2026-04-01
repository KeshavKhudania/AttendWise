<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminPermission extends Model
{
    use HasFactory;
    use SoftDeletes;
    public function children()
{
    return $this->hasMany(AdminPermission::class, 'parent_id', 'id')
                ->where('deleted_at', null)
                ->where('status', 1);
}

}
