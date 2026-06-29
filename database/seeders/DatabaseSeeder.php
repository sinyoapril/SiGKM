<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            TahunAkademikSeeder::class,
            SemesterSeeder::class,
            DosenSeeder::class,
            MataKuliahSeeder::class,
            StandarMutuSeeder::class,
            KelasSeeder::class,
            TingkatRisikoSeeder::class,
        ]);
    }
}
