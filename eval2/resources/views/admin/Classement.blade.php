@extends('template.admin_home')

@section('import-css')
    <link href=""{{ asset('assets/libs/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/table.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <a class=" btn btn-info" href="{{ url('admin/export') }}"> Export Certificat </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
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
                                        <a class="dropdown-item" href="{{ url('classementEquipe?coureur_genre=M') }}">Homme</a>
                                        <a class="dropdown-item" href="{{ url('classementEquipe?coureur_genre=F') }}">Femme</a>
                                        <a class="dropdown-item" href="{{ url('classementEquipe?categorie=Junior') }}">Junior</a>
                                        <a class="dropdown-item" href="{{ url('classementEquipe?categorie=Senior') }}">Senior</a>
                                    </div>
                                </div>
                            </div>

                            <table id="demo-foo-addrow" class="content-table col-md-12" >
                                <thead>
                                    <tr>
                                        <th class="footable-sortable"> rang <th>
                                        <th class="footable-sortable"> Equipe <th>
                                        <th class="footable-sortable"> Point total <th>
                                        <th class="footable-sortable"><th>
                                    </tr>
                                </thead>

                                <tbody>
                                @php
                                    $class = [''];
                                    $e = 0;

                                @endphp
                                @for($i =0;$i<count($coureurs);$i++)
                                    @php
                                        $e = $i+1;
                                        if ($e < count($coureurs)) {
                                            if($coureurs[$e]->place === $coureurs[$i]->place) {
                                                $class[] = 'bg-warning';
                                                $class[$i] = 'bg-warning';
                                            } else {
                                                $class[] = '';
                                            }
                                        }
                                    @endphp
                                    <tr class="footable-even {{ $class[$i] }}" >
                                        <td class="footable-sortable"> {{ $coureurs[$i]->place }} <td>
                                        <td class="footable-sortable"> {{ $coureurs[$i]->nom }} <td>
                                        <td class="footable-sortable"> {{ $coureurs[$i]->total_points }} <td>
                                        <td class="footable-sortable"> <a href="{{ url('/classement/detail/'.$coureurs[$i]->id_equipe) }}" class="btn btn-outline-info"> detail </a> <th>
                                    </tr>
                                @endfor
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
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <div class="card-body">
                        <div id="chart"></div>
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

    @php
        $rankings = json_encode($coureurs);
    @endphp
    <script>
        var donnee = {!! $rankings !!};

        var options = {
            series: donnee.map(item => item.total_points),
            chart: {
                type: 'pie',
                height: 350
            },
            labels: donnee.map(item => item.nom),
            stroke: {
                show: false
            },
            responsive: [
                {
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        }
                    }
                }
            ],
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " points";
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
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
