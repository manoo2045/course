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
                        <div class="">
                            <table id="demo-foo-addrow" class="content-table col-md-12" >
                                <thead>
                                <tr>
                                    <th class="footable-sortable"> # <th>
                                    <th class="footable-sortable"> Etape <th>
                                    <th class="footable-sortable"> Total point<th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($coureurs as $coureur)
                                    <tr class="footable-even" style="">
                                        <td class="footable-sortable"> {{ $coureur->id_etape }} <td>
                                       <td class="footable-sortable"> {{ $coureur->etape_nom }} <td>
                                        <td class="footable-sortable"> {{ $coureur->point_etape }} <td>
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
