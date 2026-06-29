<?php

namespace Database\Seeders;

use App\Models\StandarMutu;
use Illuminate\Database\Seeder;

class StandarMutuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $standarMutu = [
            [
                'kode_standar' => 'STD-001',
                'nama_standar' => 'Standar Visi Misi',
                'is_active' => true,
            ],
            [
                'kode_standar' => 'STD-002',
                'nama_standar' => 'Standar Tata Pamong dan Tata Kelola',
                'is_active' => true,
            ],
        ];

        foreach ($standarMutu as $data) {
            StandarMutu::updateOrCreate(
                [
                    'kode_standar' => $data['kode_standar'],
                    'nama_standar' => $data['nama_standar'],
                ],
                $data
            );
        }
    }
}
