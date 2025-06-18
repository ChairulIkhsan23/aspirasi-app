<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'aspirasi_id'];
    
    public function aspirasi()
    {
        return $this->belongsTo(Aspirasi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
