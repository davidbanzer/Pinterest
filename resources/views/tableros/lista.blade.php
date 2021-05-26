@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card-columns">
                @foreach($listaTableros as $objTablero)
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">{{$objTablero->nombre}}</h4>
                            <p class="card-text">Creado por: {{$objTablero->users->name}}</p>
                            @if(auth()->id() == $objTablero->users->id)
                                <small class="float-right p-2"><a href="">Eliminar</a></small>
                                <small class="float-right p-2"><a href="">Editar</a></small>
                            @endif
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
