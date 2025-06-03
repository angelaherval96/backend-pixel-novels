<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Chapter;
use App\Models\Statistic;
use App\Models\User;

class Novel extends Model
{
    /** @use HasFactory<\Database\Factories\NovelFactory> */
    use HasFactory;

    protected $guarded=[];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class)->orderBy('order', 'asc');
    }

    public function statistic()
    {
        return $this->hasOne(Statistic::class);
    }

    public function userWhoFavourited()
    {
        return $this->belongsToMany(User::class)->withTimestamps(); //necesario para manejar timestamps en la tabla intermedia y que se registre automáticamente cuando se agregue o elimine un favorito
    }
}
