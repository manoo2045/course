<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Import extends Model
{
    use HasFactory;

    public function importDonne($pathEtape,$resultat): array
    {
        $dataEtape = \Maatwebsite\Excel\Facades\Excel::toArray(new \App\Imports\Import(),storage_path($pathEtape))[0];
        $dataResultat = \Maatwebsite\Excel\Facades\Excel::toArray(new \App\Imports\Import(),storage_path($resultat))[0];
//        dd($dataResultat);

        $message = [];
        $i = 0;
        foreach ($dataEtape as $d) {
            try {
                $validation = Validator::make([
                    'etape' => $d['etape'],
                    'longueur' => $d['longueur'],
                    'nb_coureur' => $d['nb_coureur'],
                    'rang' => $d['rang'],
                    'depart' => $d['date_depart'],
                    'heure' => $d['heure_depart']
                ], [
                    'etape' => ['required'],
                    'longueur' => ['required'],
                    'nb_coureur' => ['required'],
                    'rang' => ['required'],
                    'depart' => ['required'],
                    'heure' => ['required']
                ]);

                $validation->validated();
                $dateDepart =  $d['date_depart'].' '. $d['heure_depart'];

                DB::table('etape')->insert([
                    'nom' => $d['etape'],
                    'longueur' => str_replace(',', '.',$d['longueur']),
                    'nb_coureur' => $d['nb_coureur'],
                    'rang' => $d['rang'],
                    'date' => $d['date_depart'],
                    'depart' => $dateDepart
                ]);
            } catch (\Exception $e) {
                $message[] = $e->getMessage() . ' || ligne : ' . $i;
            }
        }

        DB::table('import_resultat')->truncate();
        foreach ($dataResultat as $d) {
            try {
                $validation = Validator::make([
                    'etape_rang' => $d['etape_rang'],
                    'numero_dossard' => $d['numero_dossard'],
                    'nom' => $d['nom'],
                    'genre' => $d['nom'],
                    'date_naissance' => $d['date_naissance'],
                    'equipe' => $d['equipe'],
                    'arrivee' => $d['arrivee']
                ], [
                    'etape_rang' => ['required'],
                    'numero_dossard' => ['required'],
                    'nom' => ['required'],
                    'genre' => ['required'],
                    'date_naissance' => ['required'],
                    'equipe' => ['required'],
                    'arrivee' => ['required']
                ]);

                $validation->validated();
                DB::table('import_resultat')->insert([
                    'etape_rang' => $d['etape_rang'],
                    'numero_dossard' => $d['numero_dossard'],
                    'nom' => $d['nom'],
                    'genre' => $d['genre'],
                    'date_naissance' => $d['date_naissance'],
                    'equipe' => $d['equipe'],
                    'arrivee' => $d['arrivee']
                ]);
            } catch (\Exception $e) {
                $message[] = $e->getMessage() . ' || ligne : ' . $i;
            }
        }

        $equipes = $this->getDistinctEquipe();
        foreach ($equipes as $equipe) {
            Equipe::create([
                'nom' => $equipe->equipe,
                'login' => $equipe->equipe.'@gmail.com',
                'pwd' => Hash::make('1234')
            ]);
        }

        $err =[];
        try {
            DB::insert('insert into coureur(nom,numero,genre,date_naissance,id_equipe)
                                select ir.nom,
                                    ir.numero_dossard,
                                    ir.genre,
                                    ir.date_naissance,
                                    e.id
                                from import_resultat ir
                                    join equipe e on e.nom = ir.equipe group by ir.nom,ir.numero_dossard,ir.genre,ir.date_naissance,e.id');
        } catch (\Exception $e) {
            $message[] = $e->getMessage();
        }
        try {
            DB::insert('insert into etape_coureur(id_coureur,id_etape)
                                select
                                    c.id,
                                    e.id
                                from import_resultat ir
                                    join etape e on e.rang = ir.etape_rang
                                    join coureur c on c.nom = ir.nom group by c.id,e.id');
        } catch (\Exception $e) {
            $message[] = $e->getMessage();
        }
        try {
            DB::insert('insert into temps_coureur (id_etape,heure_depart,heure_arrive,id_coureur)
                                select
                                    e.id,
                                    e.depart,
                                    ir.arrivee,
                                    c.id
                                from import_resultat ir
                                    join etape e on e.rang = ir.etape_rang
                                    join coureur c on c.nom = ir.nom group by e.id,e.depart,ir.arrivee,c.id');
        } catch (\Exception $e) {
            $message[] = $e->getMessage();
        }

        return $message;
    }

    public function getDistinctEquipe(){
        return DB::table('import_resultat')->distinct('equipe')->get('equipe');
    }

    public function inportPoint($path) {
        $data = \Maatwebsite\Excel\Facades\Excel::toArray(new \App\Imports\Import(),storage_path($path))[0];

        $message = [];
        $i = 0;
        foreach ($data as $d) {
            try {
                $validation = Validator::make([
                    'classement' => $d['classement'],
                    'points' => $d['points']
                ], [
                    'classement' => ['required'],
                    'points' => ['required'],
                ]);

                $validation->validated();

                DB::table('parametre_point')->insert([
                    'rang' => $d['classement'],
                    'point' => $d['points'],
                ]);
            } catch (\Exception $e) {
                $message[] = $e->getMessage() . ' || ligne : ' . $i;
            }
        }

        return $message;
    }

}
