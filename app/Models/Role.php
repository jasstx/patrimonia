<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_role';
    public $incrementing = true;

    protected $fillable = [
        'nom_role',
        'description',
        'date_cree',
        'cree_par',
    ];

    protected $casts = [
        'date_cree' => 'date',
    ];

    // RELATIONS

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id');
    }

    // MÉTHODES UTILES

    public function givePermissionTo($permissionName)
    {
        $permission = Permission::where('nom_permission', $permissionName)->first();
        if ($permission) {
            $this->permissions()->syncWithoutDetaching([$permission->id_permission]);
        }
    }

    public function hasPermission($permissionName)
    {
        return $this->permissions()->where('nom_permission', $permissionName)->exists();
    }

    // SCOPES

    public function scopeActif($query)
    {
        return $query->where('est_actif', true);
    }
}
