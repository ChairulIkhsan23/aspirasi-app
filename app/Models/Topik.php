<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topik extends Model
{
    use HasFactory;

    protected $fillable = ['nama'];

    public function aspirasis()
    {
        return $this->hasMany(\App\Models\Aspirasi::class, 'topik_id');
    }

}
