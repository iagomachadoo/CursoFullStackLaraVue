{{-- A diretiva @extends serve para extender o layout padrão para as outras páginas --}}
@extends('layout.site')

{{-- Incluindo código --}}
@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">

    <style>
        p{
            margin-left: 12px;
            color: red;
        }
    </style>
@endpush

{{-- Adiciona conteúdo ao início da pilha --}}
@prepend('css')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
@endprepend

@section('title', 'Lista de usuários')

@section('sidbar')
    {{-- A diretiva @parent serve para incluirmos o valor padrão da section mais os novos valores passados --}}
    {{-- @parent --}}

    {{-- <ul>
        <li>Menu secundáio 1</li>
        <li>Menu secundáio 2</li>
        <li>Menu secundáio 3</li>
    </ul> --}}

@endsection

@section('conteudo')
    {{-- {{dd($users)}} --}}
    {{-- Renderizando um componente --}}
    {{-- <x-user></x-user> --}}

    {{-- Renderizando um componente dentor de um subdiretório --}}
    {{-- <x-user.user-list/> --}}
    
    {{-- <x-user.user-list type="card" class="bg-red"/> --}}


    <x-user.user-list type="card" :users="$users"/>


    {{-- @each('user.user', $users, 'user') --}}

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const stack = 'olá mundo!'
    </script>
@endpush