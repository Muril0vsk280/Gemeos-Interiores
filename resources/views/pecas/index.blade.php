@extends('layouts.app')


@section('title', 'Modelos - Gêmeos Interiores')


@php

    $cargoUsuario =
        auth()->user()?->cargo?->nome;

    $podeEditar = in_array(
        $cargoUsuario,
        [
            'Administrador',
            'Encarregado'
        ],
        true
    );

@endphp


@section('content')


{{-- ============================================================ --}}
{{-- CABEÇALHO --}}
{{-- ============================================================ --}}

<div class="cabecalho-listagem">

    <div class="titulo-pagina">

        <h1>
            Modelos
        </h1>

        <p>
            Encontre rapidamente o modelo que você precisa.
        </p>

    </div>


    @if ($podeEditar)

        <a
            href="{{ route('pecas.create') }}"
            class="botao"
        >
            + Novo modelo
        </a>

    @endif

</div>


{{-- ============================================================ --}}
{{-- PESQUISA PRINCIPAL --}}
{{-- ============================================================ --}}

<div class="card pesquisa-modelos">

    <form
        method="GET"
        action="{{ route('pecas.index') }}"
    >

        <label
            for="busca"
            class="pesquisa-modelos-label"
        >
            Qual modelo você procura?
        </label>


        <div class="pesquisa-modelos-principal">

            <input
                type="text"
                name="busca"
                id="busca"
                value="{{ $busca ?? '' }}"
                placeholder="Digite o nome ou código do modelo..."
                autocomplete="off"
                autofocus
            >


            <button
                type="submit"
                class="botao botao-grande"
            >
                🔍 Pesquisar
            </button>

        </div>


        {{-- ======================================================== --}}
        {{-- FILTROS SECUNDÁRIOS --}}
        {{-- ======================================================== --}}

        <details
            class="filtros-avancados"
            @if (!empty($tipoId)) open @endif
        >

            <summary>
                Mais filtros
            </summary>


            <div class="filtros-avancados-conteudo">

                <div class="campo">

                    <label for="tipo">
                        Tipo do modelo
                    </label>

                    <select
                        name="tipo"
                        id="tipo"
                    >

                        <option value="">
                            Todos os tipos
                        </option>


                        @foreach ($tipos as $tipo)

                            <option
                                value="{{ $tipo->id }}"
                                @selected(
                                    (string) ($tipoId ?? '') ===
                                    (string) $tipo->id
                                )
                            >

                                {{ $tipo->nome }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="filtros-botoes">

                    <button
                        type="submit"
                        class="botao"
                    >
                        Aplicar filtros
                    </button>


                    @if (
                        !empty($busca) ||
                        !empty($tipoId)
                    )

                        <a
                            href="{{ route('pecas.index') }}"
                            class="botao botao-secundario"
                        >
                            Limpar filtros
                        </a>

                    @endif

                </div>

            </div>

        </details>

    </form>

</div>


{{-- ============================================================ --}}
{{-- QUANTIDADE DE RESULTADOS --}}
{{-- ============================================================ --}}

<div class="resultado-modelos">

    @if ($pecas->total() === 1)

        <strong>
            1 modelo encontrado
        </strong>

    @else

        <strong>
            {{ $pecas->total() }} modelos encontrados
        </strong>

    @endif

</div>


{{-- ============================================================ --}}
{{-- RESULTADOS --}}
{{-- ============================================================ --}}

@if ($pecas->count())

    <div class="grade-listagem-modelos">

        @foreach ($pecas as $peca)

            @php

                $fotoModelo =
                    $peca->fotos
                        ->whereNull('etapa_id')
                        ->sortBy('ordem')
                        ->first();

            @endphp


            <article class="card-modelo-listagem">


                {{-- FOTO --}}

                <a
                    href="{{ route(
                        'pecas.show',
                        $peca
                    ) }}"
                    class="card-modelo-foto"
                >

                    @if ($fotoModelo)

                        <img
                            src="{{ asset(
                                'storage/' .
                                $fotoModelo->caminho
                            ) }}"
                            alt="{{ $peca->nome }}"
                            loading="lazy"
                        >

                    @else

                        <div class="card-modelo-sem-foto">

                            <span>
                                📷
                            </span>

                            <small>
                                Sem foto
                            </small>

                        </div>

                    @endif

                </a>


                {{-- INFORMAÇÕES --}}

                <div class="card-modelo-conteudo">

                    <div class="card-modelo-codigo">

                        {{ $peca->codigo }}

                    </div>


                    <h2>

                        {{ $peca->nome }}

                    </h2>


                    <div class="card-modelo-tipo">

                        {{ $peca->tipo?->nome ?? 'Sem tipo' }}

                    </div>


                    <a
                        href="{{ route(
                            'pecas.show',
                            $peca
                        ) }}"
                        class="botao card-modelo-abrir"
                    >
                        Abrir modelo
                    </a>

                </div>

            </article>

        @endforeach

    </div>


    {{-- ============================================================ --}}
    {{-- PAGINAÇÃO --}}
    {{-- ============================================================ --}}

    @if ($pecas->hasPages())

        <div class="paginacao-modelos">

            {{ $pecas->links() }}

        </div>

    @endif


@else

    <div class="card sem-modelos">

        <div class="sem-modelos-icone">
            🔍
        </div>


        <h2>
            Nenhum modelo encontrado
        </h2>


        <p>
            Tente pesquisar outro nome, código
            ou alterar os filtros.
        </p>


        @if (
            !empty($busca) ||
            !empty($tipoId)
        )

            <a
                href="{{ route('pecas.index') }}"
                class="botao botao-secundario"
            >
                Limpar pesquisa
            </a>

        @endif

    </div>

@endif


@endsection