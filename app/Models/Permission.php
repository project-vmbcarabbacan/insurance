<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Permission extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'code',
    ];

    /**
     * Define a many-to-many relationship between Permission and Role.
     *
     * This links permissions to roles using the role_permissions pivot table.
     * - permission_id = the foreign key in the pivot table pointing to this Permission
     * - role_id       = the foreign key in the pivot table pointing to the Role
     */
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_permissions',
            'permission_id',
            'role_id'
        );
    }

    /**
     * Get all AuditLog attached to this permission.
     */
    public function audits(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }
}
