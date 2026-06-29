<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TingkatRisiko;

class TingkatRisikoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tingkatRisikos = [
            ['nama_tingkat' => 'Rendah', 'keterangan' => 'Tingkat risiko rendah.'],
            ['nama_tingkat' => 'Sedang', 'keterangan' => 'Tingkat risiko sedang.'],
            ['nama_tingkat' => 'Tinggi', 'keterangan' => 'Tingkat risiko tinggi.'],
        ];

        foreach ($tingkatRisikos as $tingkat) {
            TingkatRisiko::updateOrCreate([
                'nama_tingkat' => $tingkat['nama_tingkat'],
                'keterangan' => $tingkat['keterangan']
            ], $tingkat);
        }
    }
}
