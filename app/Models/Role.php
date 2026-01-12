<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Role extends Model
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
        'slug',
        'name',
    ];

    /**
     * Scope to filter by slug
     */
    public function scopeSlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Get all users that are assigned to this role.
     *
     * This defines a one-to-many relationship where
     * a single role can be associated with multiple users.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Define a many-to-many relationship between Permission and Role.
     *
     * This links permissions to roles using the role_permissions pivot table.
     * - role_id = the foreign key in the pivot table pointing to this Permission
     * - permission_id       = the foreign key in the pivot table pointing to the Role
     */
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permissions',
            'role_id',
            'permission_id'
        );
    }

    /**
     * Get all AuditLog attached to this role.
     */
    public function audits(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }
}
