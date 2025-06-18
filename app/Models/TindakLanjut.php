<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TindakLanjut extends Model
{
    protected $table = 'tindak_lanjut';

    protected $fillable = [
        'aspirasi_id',
        'keterangan',
    ];

    public function aspirasi()
    {
        return $this->belongsTo(Aspirasi::class);
    }
}
