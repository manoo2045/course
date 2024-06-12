@extends('template.admin_home')

@section('title', 'Accueil')

@section('content')
    <a class="btn btn-danger" href="{{ url('admin/clearBd') }}"> Reinitialiser base </a>
    <br><br><br>
    <a class="btn btn-info" href="{{ url('admin/genereCategorie') }}"> Generer categorie </a>
@endsection

