<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasRole
{
    public function hasRole(string $roleSlug): bool
    {
        return $this->role?->slug === $roleSlug;
    }

    public function hasAnyRole(array $roleSlugs): bool
    {
        return in_array($this->role?->slug, $roleSlugs, true);
    }

    public function scopeRole(Builder $query, string $roleSlug): Builder
    {
        return $query->whereHas('role', function (Builder $roleQuery) use ($roleSlug) {
            $roleQuery->where('slug', $roleSlug);
        });
    }

    public function scopeAnyRole(Builder $query, array $roleSlugs): Builder
    {
        return $query->whereHas('role', function (Builder $roleQuery) use ($roleSlugs) {
            $roleQuery->whereIn('slug', $roleSlugs);
        });
    }
}
