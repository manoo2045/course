<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Equipe extends Authenticatable
{
    use Notifiable;

    protected $table = 'equipe'; // Nom de la table
    public $timestamps = false;

    protected $fillable = [
        'nom',
        'login',
        'pwd',
    ];

    protected $hidden = [
        'pwd',
    ];

    public function getAuthPassword()
    {
        return $this->pwd;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['pwd'] = Hash::make($value);
    }


    public function getAllEquipePenalise() {
        return DB::table('v_penalite')->get();
    }
}
