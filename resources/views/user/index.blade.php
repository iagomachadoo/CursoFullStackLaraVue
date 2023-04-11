{{-- A diretiva @extends serve para extender o layout padrão para as outras páginas --}}
@extends('layout.site')

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