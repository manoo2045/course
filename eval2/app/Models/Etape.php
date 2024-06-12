<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Etape extends Model
{
    use HasFactory;

    public static function getEtapeById($id){
        return DB::table('etape')
            ->where('id',$id)
            ->first();
    }

    public function getAllEtape() {
        return DB::table('etape')
            ->get();
    }

    public function getAllEtapeNomP() {
        return DB::table('etape')
            ->get();
    }

    public function getNbCoureurMax($id) {
        return DB::select('select nb_coureur from etape where id='.$id);
    }

    public function getNbCoureurEquipe($idEquipe) {
        return DB::select('select count(id) from etape where id='.$idEquipe);
    }

    public static function getDateDepart($id){
        return DB::table('etape')
            ->where('id',$id)
            ->first();
    }
}
