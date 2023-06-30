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


class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        SoftDeletes,
        HasRoles,
        HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'role_id',
        'location_id',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'permission', "group_name", "position_name",
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, "role_id", "id");
    }

    public function location()
    {
        return $this->belongsTo(Location::class, "location_id", "id");
    }

    public function getGroupNameAttribute()
    {
        if ($this->role) {
            return $this->role->name;
        }
    }

    public function position()
    {
        return $this->belongsTo(Position::class, "position_id", "id");
    }

    public function getPositionNameAttribute()
    {
        if ($this->position) {
            return $this->position->name;
        } else {
            return null;
        }
    }

    public function getPermissionAttribute()
    {
        return $this->getAllPermissions();
    }

    public function getDataParsingAttribute()
    {
        $data = collect([
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "role_id" => $this->role_id,
        ]);

        return $data;
    }
}
