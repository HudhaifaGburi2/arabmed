<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'display_name', 'description'
    ];

    public $timestamps = true;

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles')->withPivot('assigned_at');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }
}
