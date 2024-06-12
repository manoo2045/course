<?php

namespace App\Http\Controllers;

use App\Models\Coureur;
use App\Models\Etape;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccueilEquipeController extends Controller
{
    public function index() {
        return view('equipe_home');
    }

    public function list_etat() {
        $obj = new Etape();
        $etape = $obj->getAllEtape();
        return view('equipe.list_etape',[
            'etapes' => $etape
        ]);
    }

    public function etape_coureurInsertView($id) {
        $obj = new Coureur();
        $idEquipe = Auth::guard('equipe')->id();
        $coureursN = $obj->getAllCoureurEquipeNonDispo($idEquipe,$id);
        $coureursD = $obj->getAllCoureurEquipeDispo($idEquipe,$id);

        $categorie = $obj->getAllCategorie();
        return view('equipe.insert_etape_coureur',[
                "id_etape" => $id,
                'coureursD' => $coureursD,
                'coureursN' => $coureursN,
                'categories' => $categorie
            ]
        );
    }

    public function insert_etape_coureur(Request $request){
        $request->validate(
            [
                'id_etape'=> ['required'],
                'id_coureur'=> ['required'],
            ]
        );
        $idEquipe = Auth::guard('equipe')->id();
        $obj = new Coureur();
        $idEtape = $request->id_etape;
        $etape = Etape::getEtapeById($idEtape);
        $coureursN = $obj->getAllCoureurEquipeNonDispo($idEquipe,$idEtape);
        if ($etape->nb_coureur === count($coureursN)) {
            return redirect('equipe/etape_coureur/insert/'.$idEtape)->with('max',['Max de coureur atteint']);
        }

        try {
            $id = DB::table('etape_coureur')->insert([
                'id_etape'=> $request->id_etape,
                'id_coureur'=> $request->id_coureur,
            ],'id');
            return redirect('equipe/etape_coureur/insert/'.$idEtape)->with('message',['Inserer etape_coureur avec succes']);
        } catch (\Exception $e) {
//            dd($e->getMessage());
            return back()->with('errors',$e->getMessage());
        }
    }
}
