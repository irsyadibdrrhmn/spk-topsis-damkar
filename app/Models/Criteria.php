<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Criteria extends Model
{
    use HasFactory;

protected $table = 'criteria';
    protected $fillable = [
        'code',
        'name',
        'weight',
        'type',
        'description',
    ];

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}