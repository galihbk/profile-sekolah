<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $fillable = [
        'name',
        'file',
        'user_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
