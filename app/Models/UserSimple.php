<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;


class UserSimple extends Authenticatable
{
    protected $table = "users";

    public function role()
    {
        return $this->belongsTo(Role::class, "role_id", "id");
    }

    public function getGroupNameAttribute()
    {
        if ($this->role) {
            return $this->role->name;
        }
    }
}
