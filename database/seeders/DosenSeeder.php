<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dosen;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dosens = [
            [
                'nip' => '1234567890',
                'nidn' => '9876543210',
                'nama_dosen' => 'Dr. John Doe',
            ],
            [
                'nip' => '0987654321',
                'nidn' => '0123456789',
                'nama_dosen' => 'Prof. Jane Smith',
            ],
        ];

        foreach ($dosens as $data) {
            Dosen::updateOrCreate(
                [
                    'nip' => $data['nip'], 
                    'nidn' => $data['nidn']
                ],  $data
            );
        }
    }
}
