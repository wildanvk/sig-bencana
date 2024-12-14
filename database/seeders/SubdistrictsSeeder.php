<?php

namespace Database\Seeders;

use App\Models\Subdistricts;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubdistrictsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kecamatan = [
            'Kandangserang',
            'Paninggaran',
            'Lebakbarang',
            'Petungkriono',
            'Talun',
            'Doro',
            'Karanganyar',
            'Kajen',
            'Kesesi',
            'Sragi',
            'Siwalan',
            'Bojong',
            'Wonopringgo',
            'Kedungwuni',
            'Karangdadap',
            'Buaran',
            'Tirto',
            'Wiradesa',
            'Wonokerto',
        ];

        // Urutkan daftar kecamatan berdasarkan abjad
        sort($kecamatan);

        foreach ($kecamatan as $index => $nama) {
            $lastKode = Subdistricts::max('kode');
            $nextNumber = $lastKode ? (int) substr($lastKode, 4) + 1 : 1;

            Subdistricts::create([
                'kode' => 'KCM-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT),
                'nama_kecamatan' => $nama,
            ]);
        }
    }
}
