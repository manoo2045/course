@extends('template.client_home')

@section('import-css')
    <link href=""{{ asset('assets/libs/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/table.css') }}">
@endsection

@section('content')

    <div class="row">
        <div class="col-md-8">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"> Classement general </h4>
                        <div class="">

                            <div class="col-md-3">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Categorie
                                    </button>
                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 35px, 0px);">
                                        <a class="dropdown-item" href="{{ url('classementEquipe') }}">Tous</a>
                                        <a class="dropdown-item" href="{{ url('equipe/classement-equipe?coureur_genre=M') }}">Homme</a>
                                        <a class="dropdown-item" href="{{ url('equipe/classement-equipe?coureur_genre=F') }}">Femme</a>
                                        <a class="dropdown-item" href="{{ url('equipe/classement-equipe?categorie=Junior') }}">Junior</a>
                                        <a class="dropdown-item" href="{{ url('equipe/classement-equipe?categorie=Senior') }}">Senior</a>
                                    </div>
                                </div>
                            </div>
                            <table id="demo-foo-addrow" class="content-table col-md-12" >
                                <thead>
                                <tr>
                                    <th class="footable-sortable"> rang <th>
                                    <th class="footable-sortable"> Equipe <th>
                                    <th class="footable-sortable"> Point total <th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($coureurs as $coureur)
                                    <tr class="footable-even" style="">
                                        <td class="footable-sortable"> {{ $coureur->place }} <td>
                                        <td class="footable-sortable"> {{ $coureur->nom }} <td>
                                        <td class="footable-sortable"> {{ $coureur->total_points }} <td>
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
                                    {{--                                    {{ $coureurs->links('pagination::bootstrap-5') }}--}}
                                </nav>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('apexcharts/dist/apexcharts.js') }}"></script>
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
