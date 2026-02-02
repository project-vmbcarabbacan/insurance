<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Scopes\ExcludeDeletedScope;
use App\Shared\Domain\Enums\GenericStatus;
use App\Shared\Domain\Enums\RoleSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * Boot the model and register global scopes.
     *
     * This global scope ensures that records marked as `deleted`
     * are automatically excluded from all queries (e.g. get(), all(),
     * paginate()) unless explicitly removed using `withoutGlobalScope()`.
     *
     * When needed:
     * Model::withoutGlobalScope(ExcludeDeletedScope::class)->get();
     * or
     * User::withoutGlobalScopes()->get();
     *
     * This prevents accidental access to logically deleted records
     * across the application and keeps query logic centralized.
     */
    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new ExcludeDeletedScope());
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'status' => GenericStatus::class
        ];
    }

    /**
     * Scope to filter by email
     */
    public function scopeEmail(Builder $query, string $email)
    {
        return $query->where('email', $email);
    }

    /**
     * Scope to filter by status 'active'
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('status', GenericStatus::ACTIVE->value);
    }

    /**
     * Scope to filter by status 'inactive'
     */
    public function scopeInactive(Builder $query)
    {
        return $query->where('status', GenericStatus::INACTIVE->value);
    }

    /**
     * Scope to filter by status 'suspended'
     */
    public function scopeSuspended(Builder $query)
    {
        return $query->where('status', GenericStatus::SUSPENDED->value);
    }

    /**
     * Scope to filter by status 'deleted'
     */
    public function scopeDeleted(Builder $query)
    {
        return $query->withoutGlobalScope(ExcludeDeletedScope::class)->where('status', GenericStatus::DELETED->value);
    }

    /**
     * Scope a query to filter users by a search term across multiple fields.
     *
     * This scope searches for the given term in:
     * - name
     * - email
     *
     * It uses SQL LIKE with wildcards to match partial terms.
     *
     * Example usage:
     * ```php
     * User::search('vincent')->get();
     * User::search('vmbcarabbacan@gmail.com')->get();
     * ```
     *
     * @param Builder $query The Eloquent query builder
     * @param string $term The search term to match
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        $term = trim($term);

        if ($term === '') {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $like = "%{$term}%";

            $q->where('name', 'like', $like)
                ->orWhere('email', 'like', $like);
        });
    }

    /**
     * Get the role that is assigned to this user.
     *
     * This defines an inverse one-to-many relationship where
     * each user belongs to a single role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if the user has a specific role.
     *
     * Uses null-safe operator to avoid errors when the user
     * has no role assigned.
     *
     * @param string $role Role name to check (e.g. 'admin')
     * @return bool True if the user has the given role
     */
    public function hasRole(string $role): bool
    {
        return $this->role?->name === $role;
    }

    /**
     * Check if the user has a specific permission.
     *
     * Safely traverses role → permissions relationship.
     * Returns false if the user has no role or permissions.
     *
     * @param string $permission Permission code (e.g. 'leads.create')
     * @return bool True if permission exists for the user's role
     */
    public function hasPermission(string $permission): bool
    {
        return $this->role?->permissions()
            ->where('code', $permission)
            ->exists() ?? false;
    }

    /**
     * Get the customer that is assigned to this user.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get all AuditLog attached to this user.
     */
    public function audits(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    public function isSuper(): bool
    {
        return $this->role?->name === RoleSlug::SUPER_ADMIN->value;
    }

    public function isAdmin(): bool
    {
        return $this->role?->name === RoleSlug::ADMIN->value;
    }

    public function isAgent(): bool
    {
        return $this->role?->name === RoleSlug::AGENT->value;
    }

    public function isTeamLead(): bool
    {
        return $this->role?->name === RoleSlug::TEAM_LEAD->value;
    }

    public function isCustomer(): bool
    {
        return $this->role?->name === RoleSlug::CUSTOMER->value;
    }

    public function isPartner(): bool
    {
        return $this->role?->name === RoleSlug::PARTNER->value;
    }

    public function isUnderwriter(): bool
    {
        return $this->role?->name === RoleSlug::UNDERWRITER->value;
    }

    public function isClaimOfficer(): bool
    {
        return $this->role?->name === RoleSlug::CLAIMS_OFFICER->value;
    }

    public function isFinance(): bool
    {
        return $this->role?->name === RoleSlug::FINANCE->value;
    }

    public function isSupport(): bool
    {
        return $this->role?->name === RoleSlug::SUPPORT->value;
    }
}
