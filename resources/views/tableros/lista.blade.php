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
                                    <form method="POST" action="{{ route('tableros.destroy',$objTablero->id) }}">
                                        @csrf
                                        @method("DELETE")
                                        <input type="submit" class="btn text-dark float-right"
                                               onclick="return confirm('¿Está seguro que desea eliminar el tablero?')"
                                               value="Eliminar"/>
                                    </form>
                                    <p class="btn float-right "><a class="text-dark text-decoration-none" href="{{ route('tableros.edit',$objTablero->id) }}">Editar</a></p>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
