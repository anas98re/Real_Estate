<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PermissionRole extends Pivot
{
    protected $fillable = [
        'permission_id ',
        'role_id '
    ];
    protected $table = 'permission_role';

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function permission()
    {
        return $this->belongsTo(permission::class, 'permission_id');
    }
}
