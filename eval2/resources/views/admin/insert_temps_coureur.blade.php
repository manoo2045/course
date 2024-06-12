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
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Ajouter Temps_coureur </h5>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ url('admin/temps_coureur/insert') }}" method="post">
                            <div class="modal-body">
                                    @csrf
                                <input type="hidden" value="{{ $id_etape }}" name="id_etape">

                                <div class="chrono">
                                    <div class="mb-3">
                                        <label for="exampleInput_heure_depart" class="form-label">Date depart</label>
                                        <input disabled name="date_depart" value="{{ old('date_depart') }}" type="date" class="form-control @error("date_depart") is-invalid @enderror" id="exampleInput_heure_depart" aria-describedby="temps_coureurlHelp">
                                        @error("heure_depart")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="tim">
                                        <div class="col-mb-2 input-time">
                                            <label for="exampleInput_heure_depart" class="form-label">hh</label>
                                            <input disabled  name="depart_hh" value="{{ old('depart_hh') }}" type="number" class="form-control @error("depart_hh") is-invalid @enderror" id="exampleInput_heure_depart" aria-describedby="temps_coureurlHelp"
                                                    min="0" max="23"
                                            >
                                            @error("heure_depart")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>:
                                        <div class="col-mb-2 input-time">
                                            <label for="exampleInput_heure_depart" class="form-label">mm</label>
                                            <input disabled name="depart_mm" value="{{ old('depart_mm') }}" type="number" class="form-control @error("depart_mm") is-invalid @enderror" id="exampleInput_heure_depart" aria-describedby="temps_coureurlHelp"
                                                   min="0" max="59"
                                            >
                                            @error("heure_depart")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>:
                                        <div class="col-mb-2 input-time">
                                            <label for="exampleInput_heure_depart" class="form-label">ss</label>
                                            <input disabled name="depart_ss" value="{{ old('depart_ss') }}" type="number" class="form-control @error("depart_ss") is-invalid @enderror" id="exampleInput_heure_depart" aria-describedby="temps_coureurlHelp"
                                                   min="0" max="59"
                                            >
                                            @error("heure_depart")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                                <div class="chrono">
                                    <div class="mb-3">
                                        <label for="exampleInput_heure_arrive" class="form-label">Date arrive</label>
                                        <input name="date_arrive" value="{{ old('date_arrive') }}" type="date" class="form-control @error("date_arrive") is-invalid @enderror" id="exampleInput_heure_arrive" aria-describedby="temps_coureurlHelp">
                                        @error("heure_arrive")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="tim">
                                        <div class="col-mb-2 input-time">
                                            <label for="exampleInput_heure_depart" class="form-label">hh</label>
                                            <input name="arrive_hh" value="{{ old('arrive_hh') }}" type="number" class="form-control @error("arrive_hh") is-invalid @enderror" id="exampleInput_heure_depart" aria-describedby="temps_coureurlHelp"
                                                   min="0" max="23"
                                            >
                                            @error("heure_depart")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>:
                                        <div class="col-mb-2 input-time">
                                            <label for="exampleInput_heure_depart" class="form-label">mm</label>
                                            <input name="arrive_mm" value="{{ old('arrive_mm') }}" type="number" class="form-control @error("arrive_mm") is-invalid @enderror" id="exampleInput_heure_depart" aria-describedby="temps_coureurlHelp"
                                                   min="0" max="59"
                                            >
                                            @error("heure_depart")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>:
                                        <div class="col-mb-2 input-time">
                                            <label for="exampleInput_heure_depart" class="form-label">ss</label>
                                            <input name="arrive_ss" value="{{ old('arrive_ss') }}" type="number" class="form-control @error("arrive_ss") is-invalid @enderror" id="exampleInput_heure_depart" aria-describedby="temps_coureurlHelp"
                                                   min="0" max="59"
                                            >
                                            @error("heure_depart")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>


                                <div class="mb-3">
                                    <label for=\"exampleInput_id_coureur class="form-label">Coureur</label>
                                    <select name="id_coureur" class="select2 form-control custom-select @error('id_coureur') is-invalid @enderror" style="width: 100%; height:36px;" id="exampleInput_id_coureur" aria-describedby="temps_coureurlHelp">
                                    @foreach($coureurs as $coureur)
                                         <option value="{{ $coureur->id_coureur }}">{{ $coureur->nom_coureur }}</option>
                                    @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-info waves-effect">Enregistre</button>
                                <a href="{{ url('admin/etape/list') }}" type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('import-js')
    <script src="{{ asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('dist/js/pages/forms/select2/select2.init.js') }}"></script>
@endsection

