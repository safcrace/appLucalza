<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  public function permissions()
  {
    return $this->belongsToMany(Permission::class, 'roles_permissions');
  }

  /**
   * Get a list of permission associate with the role
   * @return array
   */
  public function getPermissionListAttribute()
  {
    return $this->permissions->lists('id');
  }
}
