<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class RoleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Blade::if('role', function (string $roleSlug): bool {
            return auth()->check() && auth()->user()->hasRole($roleSlug);
        });

        Blade::if('anyRole', function (array $roleSlugs): bool {
            return auth()->check() && auth()->user()->hasAnyRole($roleSlugs);
        });
    }
}
