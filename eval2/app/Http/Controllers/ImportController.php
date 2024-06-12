<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use App\Models\Finition;
use App\Models\Import;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function index() {
        return view('admin.import');
    }

    public function importPoint(Request $request) {
        $request->validate([
            'point' => ['required'],
        ]);
        $point = $request->file('point');

        try {
            $filename = "CSVP_".time().".".$point->getClientOriginalExtension();
            $path = 'data/'. $filename;

            $point->move(storage_path('data/'), $filename);

            $import = new Import();

            $error = $import->inportPoint($path);

            if (count($error) > 0){
                return back()->with([
                    'err'=> $error
                ]);
            }
            return back()->with([
                'message'=> ['Importation points termine']
            ]);
        } catch (\Exception $e) {
            $err[] = $e->getMessage();
            return back()->with('cath',$err);
        }
    }

    public function importEtapeResultat(Request $request) {
        $request->validate([
            'etape' => ['required'],
            'resultat' => ['required']
        ]);

        $etape = $request->file('etape');
        $resultat = $request->file('resultat');

        try {
            $filename1 = "CSV1_".time().".".$etape->getClientOriginalExtension();
            $filename2 = "CSV2_".time().".".$resultat->getClientOriginalExtension();
            $path1 = 'data/'. $filename1;
            $path2 = 'data/'. $filename2;
            $etape->move(storage_path('data/'), $filename1);
            $resultat->move(storage_path('data/'), $filename2);

            $import = new Import();

            $error = $import->importDonne($path1,$path2);

            if (count($error) > 0){
                return back()->with([
                    'errtm'=> $error
                ]);
            }
            return back()->with([
                'message'=> ['Import termine']
            ]);
        } catch (\Exception $e) {
            $err[] = $e->getMessage();
            return back()->with('cath',$err);
        }
    }

}
