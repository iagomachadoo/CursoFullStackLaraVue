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

@section('title', 'Lista de usuários')

@section('sidbar')
    {{-- A diretiva @parent serve para incluirmos o valor padrão da section mais os novos valores passados --}}
    @parent

    <ul>
        <li>Menu secundáio 1</li>
        <li>Menu secundáio 2</li>
        <li>Menu secundáio 3</li>
    </ul>

@endsection

@section('conteudo')

    @each('user.user', $users, 'user')

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const stack = 'olá mundo!'
    </script>
@endpush