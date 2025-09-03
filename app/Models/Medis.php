<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medis extends Model
{
    protected $fillable = [
        'user_id',
        'diagnosa_id',
        'tanggal_periksa',
        'keluhan',
        'tambahan',
        'gula_darah_tipe',
        'gula_darah_mg_dl',
        'kolesterol_mg_dl',
        'asam_urat_mg_dl',
        'berat_kg',
        'tinggi_cm',
        'imt',
        'tensi_sistolik',
        'tensi_diastolik',
        'spo2',
    ];
    protected $casts = [
        'tanggal_periksa' => 'date',
        'asam_urat_mg_dl' => 'decimal:1',
        'berat_kg'        => 'decimal:2',
        'tinggi_cm'       => 'decimal:2',
        'imt'             => 'decimal:2',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function diagnosa()
    {
        return $this->belongsTo(Diagnosa::class);
    }
}
