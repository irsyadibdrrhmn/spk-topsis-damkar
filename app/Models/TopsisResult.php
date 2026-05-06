<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TopsisResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'period',
        'positive_distance',
        'negative_distance',
        'preference_value',
        'rank',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}