<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Novel extends Model
{
    /** @use HasFactory<\Database\Factories\NovelFactory> */
    use HasFactory;

    protected $guarded=[];

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function statistics()
    {
        return $this->hasOne(Statistic::class);
    }

}
