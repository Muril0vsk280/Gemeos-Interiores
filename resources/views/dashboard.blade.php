@extends('layouts.app')

@php

    $cargoUsuario = auth()->user()?->cargo?->nome;

    $podeEditar = in_array(
        $cargoUsuario,
        [
            'Administrador',
            'Encarregado'
        ],
        true
    );

    $administrador =
        $cargoUsuario === 'Administrador';

@endphp

@section('title', 'Início - Gêmeos Interiores')


@section('content')


{{-- ============================================================ --}}
{{-- TÍTULO --}}
{{-- ============================================================ --}}

<div class="titulo-pagina">

    <h1>
        Olá, {{ auth()->user()->name }}
    </h1>

    <p>
        Encontre rapidamente as informações
        que você precisa.
    </p>

</div>


{{-- ============================================================ --}}
{{-- PESQUISA PRINCIPAL --}}
{{-- ============================================================ --}}

<div class="card area-pesquisa">

    <h2>
        Qual modelo você procura?
    </h2>


    <form
        method="GET"
        action="{{ route('pecas.index') }}"
        class="pesquisa-form"
    >

        <input
            type="text"
            name="busca"
            placeholder="Digite o nome ou código do modelo"
            autocomplete="off"
            autofocus
        >


        <button
            type="submit"
            class="botao botao-grande"
        >
            🔍 Pesquisar
        </button>

    </form>

</div>


{{-- ============================================================ --}}
{{-- AÇÕES PARA ADMINISTRADOR / ENCARREGADO --}}
{{-- ============================================================ --}}

@if ($podeEditar)

    <div class="acoes-rapidas">

        <a
            href="{{ route('pecas.create') }}"
            class="botao"
        >
            + Novo modelo
        </a>


        @if ($administrador)

            <a
                href="{{ route('usuarios.index') }}"
                class="botao botao-secundario"
            >
                Administração
            </a>

        @endif

    </div>

@endif


{{-- ============================================================ --}}
{{-- MODELOS RECENTES --}}
{{-- ============================================================ --}}

<div class="titulo-pagina">

    <h2>
        Modelos recentes
    </h2>

    <p>
        Acesse rapidamente os últimos modelos cadastrados.
    </p>

</div>


@if ($pecasRecentes->count())

    <div class="grade-modelos">

        @foreach ($pecasRecentes as $peca)

            <div class="modelo-card">

                <div class="modelo-codigo">

                    {{ $peca->codigo }}

                </div>


                <div class="modelo-nome">

                    {{ $peca->nome }}

                </div>


                <div class="modelo-tipo">

                    {{ $peca->tipo?->nome }}

                </div>


                <a
                    href="{{ route(
                        'pecas.show',
                        $peca
                    ) }}"
                    class="botao"
                >
                    Abrir modelo
                </a>

            </div>

        @endforeach

    </div>


    <div style="margin-top:25px;">

        <a
            href="{{ route('pecas.index') }}"
            class="botao botao-secundario"
        >
            Ver todos os modelos
        </a>

    </div>

@else

    <div class="card">

        <p>
            Nenhum modelo cadastrado ainda.
        </p>

    </div>

@endif


@endsection