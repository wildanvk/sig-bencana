<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DisasterTypes extends Model
{
    use HasFactory;

    public $incrementing = false; // Non-incrementing primary key
    protected $table = 'disaster_types'; // Nama tabel
    protected $keyType = 'string'; // Primary key bertipe string
    protected $primaryKey = 'kode'; // Primary key adalah 'kode'

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate kode unik, misalnya JNS-0001
            $lastKode = self::max('kode');
            $nextNumber = $lastKode ? (int) substr($lastKode, 4) + 1 : 1;
            $model->kode = 'JNS-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        });
    }

    protected $fillable = ['kode', 'jenis_bencana'];
}
