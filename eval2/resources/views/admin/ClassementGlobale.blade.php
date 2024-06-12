@extends('template.admin_home')

@section('import-css')
    <link href=""{{ asset('assets/libs/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/table.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-10">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-md-3">
                            <div class="btn-group">
                                <button type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Etape
                                </button>
                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 35px, 0px);">
                                    @foreach($etapes as $et)
                                        <a class="dropdown-item" href="{{ url('/classementGlobale/'.$et->id) }}">{{ $et->nom }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <br>
                        <h4 class="card-title"> Etape : {{ $etape->nom }}</h4>
                        <h4 class="card-title"> Depart : {{ $etape->depart }}</h4>
                        <div class="">
                            <table id="demo-foo-addrow" class="content-table col-md-12" >
                                <thead>
                                <tr>
                                    <th class="footable-sortable"> rang <th>
                                    <th class="footable-sortable"> Nom <th>
                                    <th class="footable-sortable"> Genre <th>
                                    <th class="footable-sortable"> Chrono <th>
                                    <th class="footable-sortable"> pénalité <th>
                                    <th class="footable-sortable"> temps final <th>
{{--                                    <th class="footable-sortable"> id_course <th>--}}
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($coureurs as $coureur)
                                    <tr class="footable-even" style="">
                                        <td class="footable-sortable"> {{ $coureur->place }} <td>
                                        <td class="footable-sortable"> {{ $coureur->coureur_nom }} <td>
                                        <td class="footable-sortable"> {{ $coureur->coureur_genre }} <td>
                                        <td class="footable-sortable"> {{ $coureur->heure_arrive }} <td>
{{--                                        <td class="footable-sortable"> {{ $coureur->penalite }} <td>--}}
                                        <td class="footable-sortable"> {{ $coureur->penalite }} <td>
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
                            <div class="">
                                <nav aria-label="Page navigation example">
                                    {{ $coureurs->links('pagination::bootstrap-5') }}
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
