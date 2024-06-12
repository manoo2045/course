@extends('template.client_home')

@section('import-css')
    <link href=""{{ asset('assets/libs/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/table.css') }}">
@endsection

@section('content')

    @if(session('message'))
        <div class="alert alert-success" role="alert">
            @foreach(session('message') as $message)
                {{ $message }}
            @endforeach
        </div>
    @endif
    <div class="row">
        <div class="col-md-10">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Liste  etapes </h4>
                        <div class="">
                                @foreach($etapes as $etape)
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h4 class="card-title">{{  $etape->nom  }}({{ $etape->longueur }} Km) -- {{ $etape->longueur }} </h4>
                                                        <div class="">
                                                            <table id="demo-foo-addrow" class="content-table col-md-12" >
                                                                <thead>
                                                                <tr>
                                                                    <th class="footable-sortable"> nom <th>
                                                                    <th class="footable-sortable"> numero <th>
                                                                    <th class="footable-sortable"> Chrono <th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php $idEquipe = Auth::guard('equipe')->id() ?>
                                                                @foreach(\App\Models\Coureur::getAllCoureurEquipeNonDispo($idEquipe,$etape->id) as $coureur)
                                                                    <tr class="footable-even" style="">
                                                                        <td class="footable-sortable"> {{ $coureur->coureur_nom }} <td>
                                                                        <td class="footable-sortable"> {{ $coureur->coureur_numero }} <td>
                                                                        <td class="footable-sortable"> {{ $coureur->temps_effectue_hh }} <td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                                <tfoot>
                                                                <tr>
                                                                    <td colspan="7">

                                                                    </td>
                                                                </tr>
                                                                </tfoot>
                                                            </table>
                                                            <div class="ml-2">
                                                                <a type="button" class="btn waves-effect waves-light btn-rounded btn-info mb-4" href="{{ url('/equipe/etape_coureur/insert/'.$etape->id) }}" >Ajouter</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            <div class="">
                                <nav aria-label="Page navigation example">
{{--                                    {{ $etapes->links('pagination::bootstrap-5') }}--}}
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script>
        function openModal() {
            $('#exampleModal').modal('show');
        }
    </script>

    @if(session('errors'))
        <script>
            $(document).ready(function () {
                $('#exampleModal').modal('show');
            });
        </script>
    @endif
@endsection

@section('import-js')
    <script src="{{ asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('dist/js/pages/forms/select2/select2.init.js') }}"></script>
@endsection
