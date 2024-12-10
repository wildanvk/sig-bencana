<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DisasterLists extends Model
{
    use HasFactory;

    public $incrementing = false; // Non-incrementing primary key
    protected $table = 'disaster_lists'; // Nama tabel
    protected $keyType = 'string'; // Primary key bertipe string
    protected $primaryKey = 'kode'; // Primary key adalah 'kode'

    protected $fillable = [
        'kode',
        'tanggal_kejadian',
        'kode_jenis_bencana',
        'kode_kecamatan',
        'desa',
        'penyebab',
        'dampak',
        'lokasi',
        'kk',
        'jiwa',
        'sakit',
        'hilang',
        'meninggal',
        'nilai_kerusakan',
        'upaya',
        'foto',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate kode unik, misalnya BNC-0001
            $lastKode = self::max('kode');
            $nextNumber = $lastKode ? (int) substr($lastKode, 4) + 1 : 1;
            $model->kode = 'BNC-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        });
    }

    public function disasterTypes()
    {
        return $this->belongsTo(DisasterTypes::class, 'kode_jenis_bencana', 'kode');
    }

    public function subdistricts()
    {
        return $this->belongsTo(Subdistricts::class, 'kode_kecamatan', 'kode');
    }
}
