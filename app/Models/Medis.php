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
