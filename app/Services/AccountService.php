<?php

namespace App\Services;

use App\Models\Dosen;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AccountService
{
    public function createRoleAccount(
        Dosen $dosen,
        string $roleSlug,
        string $name,
        string $email,
        string $password,
    ): User {
        return DB::transaction(function () use ($dosen, $roleSlug, $name, $email, $password) {
            $role = Role::query()
                ->where('slug', $roleSlug)
                ->firstOrFail();

            $accountAlreadyExists = User::query()
                ->where('dosen_id', $dosen->id)
                ->where('role_id', $role->id)
                ->exists();

            if ($accountAlreadyExists) {
                throw ValidationException::withMessages([
                    'role' => 'Dosen ini sudah memiliki akun untuk role tersebut.',
                ]);
            }

            return User::query()->create([
                'role_id' => $role->id,
                'dosen_id' => $dosen->id,
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'is_active' => true,
            ]);
        });
    }
}
