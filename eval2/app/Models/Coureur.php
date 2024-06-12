<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Coureur extends Model
{
    use HasFactory;

    public static function getCoureurById($id){
        return DB::table('v_coureur')
            ->where('id',$id)
            ->first();
    }

    public function getAllCoureurEquipeDispo($id,$idEtape) {
        return DB::select('select * from coureur where id not in (select id_coureur FROM etape_coureur where id_etape='.$idEtape.') and id_equipe = '.$id);
    }

    public static function getAllCoureurEquipeNonDispo($id,$idEtape) {
        $req = 'select * from v_temps_coureur_etape_rank where id_coureur in (select id_coureur FROM etape_coureur where id_equipe='.$id.') and id_equipe = '.$id." and id_etape = ".$idEtape;
//        dd($req);
        return DB::select($req);
    }

    public static function getAllCoureur() {
        return DB::table('v_coureur')->get();
    }

    public static function getCategorieById($id){
        return DB::table('categorie')
            ->where('id',$id)
            ->first();
    }

    public function getAllCategorie() {
        return DB::table('categorie')
            ->get();
    }

    public function getEtapeCoureur($id) {
        return DB::table('v_etape_coureur')
            ->where('id_etape',$id)
            ->get();
    }

    public function getClassement($idEtape) {
        return DB::table('v_temps_coureur_etape_point')
            ->where('id_etape',$idEtape)
            ->where('id_course',1)
            ->orderBy('place')
            ->paginate(6);
    }

    public function getClassementEquipe() {
        return DB::table('v_classement_equipe')
//            ->where('id_course',1)
            ->paginate(6);
    }

    public static function getCategorie($id) {
        $c = self::getCoureurById($id);

        $age = (int)$c->age;
        if ($age > 18) {
            return 'SENIOR';
        } else {
            return 'JUNIOR';
        }
    }
    public static function genererAllCategorie() {
        $coureurs = Coureur::getAllCoureur();

        foreach ($coureurs as $coureur) {
            $categorie = Coureur::getCategorie($coureur->id);
            DB::table('categorie_coureur')->updateOrInsert([
                'id_coureur' => $coureur->id,
                'categorie' => $categorie,
            ]);
        }
    }

}
