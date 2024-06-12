@extends('template.admin_home')

@section('title', '')

@section('content')
    <div class="col-md-5">
        <div class="card">
            @if(session('errtm'))
                @foreach(session('errtm') as $message)
                    <div class="alert alert-danger" role="alert">
                        {{ $message }}
                    </div>
                @endforeach
            @endif

            @if(session('message'))
                <div class="alert alert-success" role="alert">
                    @foreach(session('message') as $message)
                        {{ $message }}
                    @endforeach
                </div>
            @endif

            @if(session('cath'))
                @foreach(session('cath') as $message)
                    <div class="alert alert-danger" role="alert">
                        {{ $message }}
                    </div>
                @endforeach
            @endif

            <div class="card-body">
                <div class="card-title" >
                    <h3> Importation donne </h3>
                </div>
                <form action="{{ url('/admin/import/data') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label> Etape </label>
                        <input type="file" placeholder="Etape" class="form-control"
                               id="exampleInputPassword1"
                               name="etape">
                        @error("etape")
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label> Resultat </label>
                        <input type="file" placeholder="Resultat" class="form-control @error("resultat") is-invalid @enderror" id="exampleInputPassword1" name="resultat">
                        @error("resultat")
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Importer</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            @if(session('err'))
                @foreach(session('err') as $message)
                    <div class="alert alert-danger" role="alert">
                        {{ $message }}
                    </div>
                @endforeach
            @endif

            <div class="card-body">
                <div class="card-title" >
                    <h3> Importation point </h3>
                </div>
                <form action="{{ url('admin/import/point') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="file" class="form-control @error("point") is-invalid @enderror" id="exampleInputPassword1" name="point">
                        @error("point")
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Importer</button>
                </form>
            </div>
        </div>
    </div>
@endsection
