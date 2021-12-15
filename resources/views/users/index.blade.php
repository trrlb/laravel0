@extends('layout')

@section('title', 'Usuarios')

@section('content')
    <h1>{{ trans('users.title.' . $view) }}</h1>
    <p>
        @if($view == 'index')
            <a href="{{ route('users.trashed') }}" class="btn btn-outline-dark">Ver papelera</a>
            <a href="{{ route('users.create') }}" class="btn btn-primary">Nuevo usuario</a>
        @else
            <a href="{{ route('users.index') }}" class="btn btn-outline-dark">Regresar al listado de usuarios</a>
        @endif
    </p>

    @includeWhen($view == 'index', 'users._filters')

    @if( $users->count() )
        <div class="table-responsive-lg">
            <table class="table table-sm">
                <thead class="thead-dark">
                <tr>
                    <th scope="col"># <span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th>
                    <th scope="col"><a href="{{ $sortable->url('first_name') }}" class="{{ $sortable->classes('first_name') }}">Nombre </a></th>
                    <th scope="col"><a href="{{ $sortable->url('email') }}" class="{{ $sortable->classes('email') }}">Correo </a></th>
                    <th scope="col">Rol <span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th>
                    <th scope="col"><a href="{{ $sortable->url('created_at') }}" class="{{ $sortable->classes('created_at') }}">Fechas </a></th>
                    <th scope="col" class="text-right th-actions">Acciones</th>
                </tr>
                </thead>
                <tbody>
                    @each('users._row', $users, 'user')
                </tbody>
            </table>
            {{ $users->links() }}
        </div>
    @else
        <p>No hay usuarios registrados</p>
    @endif
@endsection
