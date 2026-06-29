<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TahunAkademik;

class TahunAkademikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunAkademik = [
            [
                'nama' => '2026/2027',
                'tanggal_mulai' => '2026-08-01',
                'tanggal_selesai' => '2027-07-31',
                'is_active' => true,
            ],
        ];

        foreach ($tahunAkademik as $data) {
            TahunAkademik::updateOrCreate(
                ['nama' => $data['nama']],
                [
                    'tanggal_mulai' => $data['tanggal_mulai'],
                    'tanggal_selesai' => $data['tanggal_selesai'],
                    'is_active' => $data['is_active'],
                ]
            );
        }
    }
}
