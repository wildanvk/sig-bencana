<?php

namespace Database\Seeders;

use App\Models\DisasterTypes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DisasterTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisBencana = [
            'Banjir Rob',
            'Tanah Longsor',
            'Gempa Bumi',
            'Kebakaran Hutan',
            'Kebakaran Lahan',
            'Angin Kencang',
            'Puting Beliung',
            'Banjir Bandang',
            'Kebakaran Bangunan',
        ];

        // Urutkan daftar jenisBencana berdasarkan abjad
        sort($jenisBencana);

        foreach ($jenisBencana as $index => $nama) {
            $lastKode = DisasterTypes::max('kode');
            $nextNumber = $lastKode ? (int) substr($lastKode, 4) + 1 : 1;

            DisasterTypes::create([
                'kode' => 'JNS-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT),
                'jenis_bencana' => $nama,
            ]);
        }
    }
}
