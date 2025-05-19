<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reading;
use App\Models\Novel;

class Chapter extends Model
{
    /** @use HasFactory<\Database\Factories\ChapterFactory> */
    use HasFactory;
    
    protected $guarded=[];

    public function readings()
    {
        return $this->hasMany(Reading::class);
    }

    public function novel()
    {
        return $this->belongsTo(Novel::class);
    }
}
