<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $ketuaRole = Role::where('slug', 'ketua-gkm')->first();

        User::updateOrCreate(
            ['email' => 'ketua@gkm.test'],
            [
                'role_id' => $ketuaRole->id,
                'dosen_id' => null,
                'name' => 'Ketua GKM',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
    }
}