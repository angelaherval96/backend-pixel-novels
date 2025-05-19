<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Chapter;
use App\Models\Reading;
use App\Models\Novel;



class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function favouriteNovels()
    {
        return $this->belongsToMany(Novel::class)->withTimestamps(); //necesario para manejar timestamps en la tabla intermedia y que se registre automáticamente cuando se agregue o elimine un favorito
    }

    public function readings()
    {
        return $this->hasMany(Reading::class);
    }

    //Para acceder a capítulos leídos con los datos de lectura incluidos. Con el withPivot se accede a los campos de la tabla intermedia
    public function chaptersRead()
    {
        return $this->belongsToMany(Chapter::class, 'readings')->withPivot('progress', 'read_at')->withTimestamps();
    }
}
