<?php

namespace App\Http\Controllers;

use App\Models\Coureur;
use App\Models\Equipe;
use App\Models\Etape;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
class AccueilAdmin extends Controller
{
    public function home() {
        return view('admin.home');
    }

    public function clearBd() {
        $tables = Schema::getAllTables();
        DB::statement('SET session_replication_role = replica');

        DB::beginTransaction();
        try {
            foreach ($tables as $table) {
//                dd($table->tablename);
                DB::table($table->tablename)->truncate();
//                DB::statement('ALTER SEQUENCE' . $table . '_id_seq RESTART WITH 1');
            }

            DB::commit();

            return redirect('home');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        } finally {
            DB::statement('SET session_replication_role = DEFAULT');
            $user = User::create([
                'pseudo' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin'),
                'is_admin' => true
            ]);
        }
    }

    public function list_etat() {
        $obj = new Etape();
        $etape = $obj->getAllEtape();
        return view('admin.list_etape',[
            'etapes' => $etape
        ]);
    }

    public function genererCategorie() {
        Coureur::genererAllCategorie();
        return redirect('home');
    }


    public function list_equipe_penaliser() {
        $obj = new Equipe();
        $equipeP = $obj->getAllEquipePenalise();
        $e = new Etape();
        $etapes = $e->getAllEtape();
        $equipes = Equipe::all();
        return view('admin.equipe_penalise',[
            'equipes' => $equipes,
            'equipe_penalises' => $equipeP,
            'etapes' => $etapes
        ]);
    }

    public function insert_penalite(Request $request) {
        $request->validate(
            [
                'etape'=> ['required'],
                'hh' => ['required', 'numeric', 'between:0,23'],
                'mm' => ['required', 'numeric', 'between:0,59'],
                'ss' => ['required', 'numeric', 'between:0,59'],
                'equipe'=> ['required'],
            ]
        );
        $heure = sprintf("%02d:%02d:%02d", $request->input('hh'), $request->input('mm'), $request->input('ss'));

        DB::table('penalite')->insert([
            'id_etape' => $request->etape,
            'id_equipe' => $request->equipe,
            'heure_penalite' => $heure
        ]);
        return redirect('/admin/penalite')->with('message',['OK']);
    }

    public function detete_penalite($id) {
        DB::delete('DELETE FROM penalite where id = '.$id);
        return redirect('/admin/penalite')->with('message',['OK']);
    }

    public function export() {
        $resultats = Session::get('resultats');

        $categorie = '';
        if (Session::has('categorie')) {
            $categorie = Session::get('categorie');
        } else {
            $categorie = 'TOUS';
        }

        $pdf = Pdf::loadView('admin.certificat',[
            'etape' => $resultats[0],
            'categorie' => $categorie
        ])->setPaper('landscape', 'A4');
//        return view('admin.certificat',[
//            'etape' => $resultats[0]
//        ]);
        return $pdf->download('certificat.pdf');
    }

}
