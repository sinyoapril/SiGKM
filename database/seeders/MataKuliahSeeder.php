<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MataKuliah;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mataKuliah = [
            [
                'kode_mk' => 'MK001',
                'nama_mk' => 'Algoritma dan Pemrograman',
                'sks' => 3,
                'is_active' => true,
            ],
            [
                'kode_mk' => 'MK002',
                'nama_mk' => 'Kalkulus',
                'sks' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($mataKuliah as $data) {
            MataKuliah::updateOrCreate(
                [
                    'kode_mk' => $data['kode_mk'],
                    'nama_mk' => $data['nama_mk'],
                    'sks' => $data['sks'],
                    'is_active' => $data['is_active'],
                ],
                $data
            );
        }
    }
}
