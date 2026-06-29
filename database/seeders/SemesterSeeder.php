<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Semester;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $semesters = [
            [
                'nama' => 'Ganjil',
                'tahun_akademik_id' => 1,
                'tanggal_mulai' => '2026-08-01',
                'tanggal_selesai' => '2027-01-31',
            ],
            [
                'nama' => 'Genap',
                'tahun_akademik_id' => 1,
                'tanggal_mulai' => '2027-02-01',
                'tanggal_selesai' => '2027-07-31',
            ],
        ];

        foreach ($semesters as $data) {
            Semester::updateOrCreate(
                ['nama' => $data['nama'], 'tahun_akademik_id' => $data['tahun_akademik_id']],
                $data
            );
        }
    }
}
