@extends('template.admin_home')

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
                            <table id="demo-foo-addrow" class="content-table col-md-12" >
                                <thead>
                                    <tr>
									<th class="footable-sortable"> nom <th>
									<th class="footable-sortable"> longueur <th>
									<th class="footable-sortable"> Max coureur <th>
									<th class="footable-sortable"> rang <th>
									<th class="footable-sortable"> date <th>
{{--									<th class="footable-sortable"> id_course <th>--}}

                                    <th class="footable-sortable">  <th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($etapes as $etape)
                                    <tr class="footable-even" style="">

                                        <td class="footable-sortable"> {{ $etape->nom }} <td>
                                        <td class="footable-sortable"> {{ $etape->longueur }} <td>
                                        <td class="footable-sortable"> {{ $etape->nb_coureur }} <td>
                                        <td class="footable-sortable"> {{ $etape->rang }} <td>
                                        <td class="footable-sortable"> {{ $etape->date }} <td>
{{--									<td class="footable-sortable"> {{ $etape->id_course }} <td>--}}

                                        <td>
                                            <div>
                                                <a href="{{ url('/admin/temps_coureur/insert/'.$etape->id) }}" type="button" class="btn btn-info">Temps</a>
                                                <a href="{{ url('/classementGlobale/'.$etape->id) }}" type="button" class="btn btn-info">Classement</a>

                                            </div>
                                        </td>
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
