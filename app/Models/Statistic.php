<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Novel;

class Statistic extends Model
{
    /** @use HasFactory<\Database\Factories\StatisticFactory> */
    use HasFactory;

    protected $guarded=[];

    public function novel()
    {
        return $this->belongsTo(Novel::class);
    }
}
