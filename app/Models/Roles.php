<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory, HasUuids;
    protected $table= 'user_roles';
    protected $fillable = ['name', 'status', 'deleted'];

    public function permissions()
    {
        return $this->belongsToMany(Permissions::class, 'role_has_permissions', 'role_id', 'permission_id')->withPivot(['create','read','update','delete'])->as('access')->where('deleted', 0)->where('status', 1);
    }
}
