<?php

namespace App\Models;

use App\Models\Subdistricts;
use App\Models\DisasterTypes;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
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
        'kk',
        'jiwa',
        'sakit',
        'hilang',
        'meninggal',
        'nilai_kerusakan',
        'upaya',
        'foto',
        'location',
        'id_user',
    ];

    protected $appends = [
        'location',
    ];

    public function getLocationAttribute(): array
    {
        return [
            "lat" => (float)$this->latitude,
            "lng" => (float)$this->longitude,
        ];
    }

    public function setLocationAttribute(?array $location): void
    {
        if (is_array($location)) {
            $this->attributes['latitude'] = $location['lat'];
            $this->attributes['longitude'] = $location['lng'];
            unset($this->attributes['location']);
        }
    }

    public static function getLatLngAttributes(): array
    {
        return [
            'lat' => 'latitude',
            'lng' => 'longitude',
        ];
    }

    public static function getComputedLocation(): string
    {
        return 'location';
    }

    public function disasterTypes()
    {
        return $this->belongsTo(DisasterTypes::class, 'kode_jenis_bencana', 'kode');
    }

    public function subdistricts()
    {
        return $this->belongsTo(Subdistricts::class, 'kode_kecamatan', 'kode');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate kode unik, misalnya BNC-0001
            $lastKode = self::max('kode');
            $nextNumber = $lastKode ? (int) substr($lastKode, 4) + 1 : 1;
            $model->kode = 'BNC-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        });

        // static::saving(function ($model) {


        //     if (isset($model->latitude, $model->longitude)) {
        //         $model->koordinat = DB::raw("ST_GeomFromText('POINT({$model->longitude} {$model->latitude})')");
        //     }
        // });
    }

    protected static function booted()
    {
        // Event saat data dihapus
        static::deleting(function ($model) {
            optional($model->foto, function ($fotoPath) {
                Storage::disk('public')->delete($fotoPath);
            });
        });

        // Event saat data diperbarui (update)
        static::updating(function ($model) {
            // Periksa apakah kolom 'foto' diubah
            if ($model->isDirty('foto')) {
                // Ambil nilai lama dari kolom 'foto'
                $oldFoto = $model->getOriginal('foto');

                // Hapus file lama jika tidak null dan merupakan string valid
                if (!is_null($oldFoto) && !empty($oldFoto) && is_string($oldFoto)) {
                    Storage::disk('public')->delete($oldFoto);
                }
            }
        });
    }
}
