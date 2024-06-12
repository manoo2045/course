<?php

namespace App\Http\Controllers;

use App\Models\Coureur;
use App\Models\Etape;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CoureurController extends Controller
{

    public function temps_coureurInsertView($id) {
        $obj = new Coureur();
        $coureurs = $obj->getEtapeCoureur($id);
        return view('admin.insert_temps_coureur',[
            'coureurs' => $coureurs,
            'id_etape' => $id
        ]);
    }

    public function insert(Request $request){
        $request->validate(
            [
//                'date_depart'=> ['required'],
//                'depart_hh' => ['required', 'numeric', 'between:0,23'],
//                'depart_mm' => ['required', 'numeric', 'between:0,59'],
//                'depart_ss' => ['required', 'numeric', 'between:0,59'],
                'date_arrive'=> ['required'],
                'arrive_hh' => ['required', 'numeric', 'between:0,23'],
                'arrive_mm' => ['required', 'numeric', 'between:0,59'],
                'arrive_ss' => ['required', 'numeric', 'between:0,59'],
                'id_coureur'=> ['required'],
            ]
        );
        $idEtape = $request->id_etape;
        $etape = Etape::getDateDepart($idEtape);

        $date_depart = $etape->depart;

        $date_arrive = Carbon::createFromFormat('Y-m-d', $request->date_arrive);

        $date_arrive->setTime($request->arrive_hh, $request->arrive_mm, $request->arrive_ss);

        $depart = $date_depart;
        $arrive = $date_arrive;

        try {
            $id = DB::table('temps_coureur')->insert([
                'heure_depart'=> $depart,
                'heure_arrive'=> $arrive,
                'id_etape'=> $idEtape,
                'id_coureur'=> $request->id_coureur,
            ]);
            return redirect('/admin/temps_coureur/insert/'.$idEtape)->with('message',['Inserer temps_coureur avec succes']);
        } catch (\Exception $e) {
            return back()->with('errors',$e->getMessage());
        }
    }


//    PAR ETAPE
    public function classement() {
        $idEtape = 1;
        $etape = Etape::getEtapeById($idEtape);
        $e = new Etape();
        $etapes = $e->getAllEtapeNomP();
//        dd($etapes);
        $obj = new Coureur();
        $coureurs = $obj->getClassement($idEtape);
        if (Auth::guard('equipe')->check()) {
            return view('ClassementGlobale',[
                'coureurs' => $coureurs,
                'etape' => $etape,
                'etapes' => $etapes
            ]);
        }
        return view('admin.ClassementGlobale',[
            'coureurs' => $coureurs,
            'etape' => $etape,
            'etapes' => $etapes
        ]);
    }

//    PAR ETAPE
    public function classementCoureur($id) {
        $idEtape = $id;
        $etape = Etape::getEtapeById($idEtape);
        $e = new Etape();
        $etapes = $e->getAllEtapeNomP();
//        dd($etapes);
        $obj = new Coureur();
        $coureurs = $obj->getClassement($idEtape);
        if (Auth::guard('equipe')->check()) {
            return view('ClassementGlobale',[
                'coureurs' => $coureurs,
                'etape' => $etape,
                'etapes' => $etapes
            ]);
        }
        return view('admin.ClassementGlobale',[
            'coureurs' => $coureurs,
            'etape' => $etape,
            'etapes' => $etapes
        ]);
    }

//    public function classementEquipe(Request $request) {
//        $obj = new Coureur();
//        $coureurs = $obj->getClassementEquipe();
//        if (Auth::guard('equipe')->check()) {
//            return view('equipe.Classement',[
//                'coureurs' => $coureurs
//            ]);
//        }
//        return view('admin.Classement',[
//            'coureurs' => $coureurs,
//        ]);
//    }

    public function classementEquipe_admin(Request $request)
    {
        Session::forget('coureur_genre');
        Session::forget('categorie');

        // Obtenir les paramètres de requête
        $categorie = strtoupper($request->input('categorie'));
        $coureur_genre = strtoupper($request->input('coureur_genre'));

        // Construire la condition WHERE dynamiquement
        $condition = '';
        $bindings = [];
        $partion = '';

        if ($categorie) {
            Session::put('categorie',$categorie);
            Session::forget('coureur_genre');
            $condition = "categorie = :categorie";
            $partion = ',categorie';
            $bindings['categorie'] = $categorie;
        } elseif ($coureur_genre) {
            Session::put('coureur_genre',$coureur_genre);
            Session::forget('categorie');
            $partion =',coureur_genre';
            $condition = "coureur_genre = :coureur_genre";
            $bindings['coureur_genre'] = $coureur_genre;
        }

        // Construire la clause WHERE
        $whereClause = '';
        if (!empty($condition)) {
            $whereClause = 'WHERE ' . $condition;
        }

        $sql = "
            SELECT
                id_equipe,
                ep.nom,
                SUM(point_2) AS total_points,
                DENSE_RANK() OVER (ORDER BY SUM(point_2) DESC) place
            FROM (
                SELECT
                    v.*,
                    DENSE_RANK() OVER (ORDER BY v.temps_effectue_mm ASC) AS place_2,
                    COALESCE(p.point, 0) AS point_2
                FROM
                    (SELECT
                         *,
                         DENSE_RANK() OVER (PARTITION BY id_etape ".$partion." ORDER BY temps_effectue_mm ASC) AS place_2
                     FROM
                        v_temps_coureur_etape_rank vr
                        JOIN categorie_coureur cc on cc.id_coureur = vr.id_coureur
                     $whereClause) v
                LEFT JOIN
                    parametre_point p
                ON
                    v.place_2 = p.rang
            ) AS subquery
            JOIN
                equipe ep ON ep.id = id_equipe
            GROUP BY
                id_equipe, ep.nom
            ORDER BY
                total_points DESC;
        ";

        $results = DB::select($sql, $bindings);
        Session::put('resultats',$results);
        return view('admin.Classement',[
            'coureurs' => $results,
        ]);
    }

    public function classementEquipe(Request $request)
    {
        // Obtenir les paramètres de requête
        $categorie = strtoupper($request->input('categorie')); // Paramètre optionnel
        $coureur_genre = strtoupper($request->input('coureur_genre')); // Paramètre optionnel

        // Construire la condition WHERE dynamiquement
        $condition = '';
        $bindings = [];
        $partion = '';
        if ($categorie) {
            $condition = "categorie = :categorie";
            $partion = ',categorie';
            $bindings['categorie'] = $categorie;
        } elseif ($coureur_genre) {
            $partion =',coureur_genre';
            $condition = "coureur_genre = :coureur_genre";
            $bindings['coureur_genre'] = $coureur_genre;
        }

        // Construire la clause WHERE
        $whereClause = '';
        if (!empty($condition)) {
            $whereClause = 'WHERE ' . $condition;
        }

        $sql = "
            SELECT
                id_equipe,
                ep.nom,
                SUM(point_2) AS total_points,
                DENSE_RANK() OVER (ORDER BY SUM(point_2) DESC) place
            FROM (
                SELECT
                    v.*,
                    DENSE_RANK() OVER (ORDER BY v.temps_effectue_mm ASC) AS place_2,
                    COALESCE(p.point, 0) AS point_2
                FROM
                    (SELECT
                         *,
                         DENSE_RANK() OVER (PARTITION BY id_etape ".$partion." ORDER BY temps_effectue_mm ASC) AS place_2
                     FROM
                        v_temps_coureur_etape_rank vr
                        JOIN categorie_coureur cc on cc.id_coureur = vr.id_coureur
                     $whereClause) v
                LEFT JOIN
                    parametre_point p
                ON
                    v.place_2 = p.rang
            ) AS subquery
            JOIN
                equipe ep ON ep.id = id_equipe
            GROUP BY
                id_equipe, ep.nom
            ORDER BY
                total_points DESC;
        ";

        $results = DB::select($sql, $bindings);

        return view('equipe.Classement',[
            'coureurs' => $results,
        ]);
    }

    public function detailClassement($id) {
        $categorie = Session::get('categorie');
        $coureur_genre = Session::get('coureur_genre');

        // Construire la condition WHERE dynamiquement
        $condition = '';
        $bindings = [];
        $partion = '';

        if ($categorie) {
            Session::put('categorie',$categorie);
            $condition = "categorie = :categorie";
            $partion = ',categorie';
            $bindings['categorie'] = $categorie;
        } elseif ($coureur_genre) {
            $partion =',coureur_genre';
            Session::put('coureur_genre',$categorie);
            $condition = "coureur_genre = :coureur_genre";
            $bindings['coureur_genre'] = $coureur_genre;
        }

        // Construire la clause WHERE
        $whereClause = '';
        if (!empty($condition)) {
            $whereClause = 'WHERE ' . $condition;
        }


        $sql = "SELECT pp.id_etape,pp.etape_nom,sum(point_2) point_etape FROM
                    (SELECT
                    v.*,
                    DENSE_RANK() OVER (ORDER BY v.temps_effectue_mm ASC) AS place_2,
                    COALESCE(p.point, 0) AS point_2
                        FROM
                        (SELECT
                            *,
                            DENSE_RANK() OVER (PARTITION BY id_etape".$partion." ORDER BY temps_effectue_mm ASC) AS place_2
                            FROM
                            v_temps_coureur_etape_rank vr
                            JOIN categorie_coureur cc on cc.id_coureur = vr.id_coureur
                            $whereClause
                        ) v
                    LEFT JOIN
                    parametre_point p
                        ON
                        v.place_2 = p.rang where id_equipe = ".$id." ) pp  group by pp.id_etape,pp.etape_nom";



        $results = DB::select($sql, $bindings);
        return view('admin.detail_classement',[
            'coureurs' => $results,
        ]);
    }
}
