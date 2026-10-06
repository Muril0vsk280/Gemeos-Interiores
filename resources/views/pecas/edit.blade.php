@extends('layouts.app')


@section('title', 'Editar ' . $peca->codigo)


@php

    $fotoPrincipal =
        $peca->fotos
            ->whereNull('etapa_id')
            ->sortBy('ordem')
            ->first();

@endphp


@section('content')


<div style="margin-bottom:20px;">

    <a
        href="{{ route(
            'pecas.show',
            $peca
        ) }}"
        class="botao botao-secundario"
    >
        ← Voltar para o modelo
    </a>

</div>


<div class="titulo-pagina">

    <h1>
        Editar modelo
    </h1>

    <p>

        {{ $peca->codigo }}
        —
        {{ $peca->nome }}

    </p>

</div>


{{-- ============================================================ --}}
{{-- FOTO PRINCIPAL --}}
{{-- ============================================================ --}}

<div
    class="card"
    style="margin-bottom:25px;"
>

    <h2>
        Foto principal
    </h2>

    <p style="color:var(--cor-texto-suave);">

        Esta é a primeira imagem usada para
        identificar o modelo.

    </p>


    @if ($fotoPrincipal)

        <div class="foto-principal-edicao">

            <img
                src="{{ asset(
                    'storage/' .
                    $fotoPrincipal->caminho
                ) }}"
                alt="{{ $peca->nome }}"
            >

        </div>


        {{-- TROCAR --}}

        <details
            class="container-expansivel"
            style="
                margin-top:20px;
                box-shadow:none;
            "
        >

            <summary>
                Trocar foto principal
            </summary>


            <div class="container-conteudo">

                <form
                    method="POST"
                    action="{{ route(
                        'pecas.foto-principal.update',
                        [
                            $peca,
                            $fotoPrincipal
                        ]
                    ) }}"
                    enctype="multipart/form-data"
                >

                    @csrf
                    @method('PUT')


                    <div class="campo">

                        <label for="foto">
                            Escolha a nova foto
                        </label>

                        <input
                            type="file"
                            name="foto"
                            id="foto"
                            accept="image/*"
                            required
                        >

                    </div>


                    <button
                        type="submit"
                        class="botao"
                    >
                        Trocar foto
                    </button>

                </form>

            </div>

        </details>


        {{-- EXCLUIR --}}

        <div
            class="zona-perigo"
            style="margin-top:20px;"
        >

            <h3>
                Excluir foto principal
            </h3>

            <p>
                O modelo continuará cadastrado,
                mas ficará sem foto principal.
            </p>


            <form
                method="POST"
                action="{{ route(
                    'pecas.fotos.destroy',
                    [
                        $peca,
                        $fotoPrincipal
                    ]
                ) }}"
                onsubmit="
                    return confirm(
                        'Tem certeza que deseja excluir a foto principal?'
                    );
                "
            >

                @csrf
                @method('DELETE')


                <input
                    type="hidden"
                    name="origem"
                    value="edit"
                >


                <button
                    type="submit"
                    class="botao botao-perigo"
                >
                    Excluir foto principal
                </button>

            </form>

        </div>


    @else

        <div class="sem-informacao">

            Este modelo ainda não possui
            foto principal.

        </div>


        <details
            class="container-expansivel"
            style="
                margin-top:20px;
                box-shadow:none;
            "
        >

            <summary>
                Adicionar foto principal
            </summary>


            <div class="container-conteudo">

                <form
                    method="POST"
                    action="{{ route(
                        'pecas.foto-principal.store',
                        $peca
                    ) }}"
                    enctype="multipart/form-data"
                >

                    @csrf


                    <div class="campo">

                        <label>
                            Escolha uma foto
                        </label>

                        <input
                            type="file"
                            name="foto"
                            accept="image/*"
                            required
                        >

                    </div>


                    <button
                        type="submit"
                        class="botao"
                    >
                        Adicionar foto principal
                    </button>

                </form>

            </div>

        </details>

    @endif

</div>


{{-- ============================================================ --}}
{{-- INFORMAÇÕES DO MODELO --}}
{{-- ============================================================ --}}

<div class="card">

    <h2>
        Informações do modelo
    </h2>


    <form
        method="POST"
        action="{{ route(
            'pecas.update',
            $peca
        ) }}"
    >

        @csrf
        @method('PUT')


        <div class="form-grid">

            <div class="campo">

                <label for="codigo">
                    Código
                </label>

                <input
                    type="text"
                    id="codigo"
                    name="codigo"
                    value="{{ old(
                        'codigo',
                        $peca->codigo
                    ) }}"
                    required
                >

            </div>


            <div class="campo">

                <label for="nome">
                    Nome do modelo
                </label>

                <input
                    type="text"
                    id="nome"
                    name="nome"
                    value="{{ old(
                        'nome',
                        $peca->nome
                    ) }}"
                    required
                >

            </div>


            <div class="campo campo-largo">

                <label for="tipo_peca_id">
                    Tipo da peça
                </label>

                <select
                    name="tipo_peca_id"
                    id="tipo_peca_id"
                    required
                >

                    @foreach ($tipos as $tipo)

                        <option
                            value="{{ $tipo->id }}"
                            @selected(
                                old(
                                    'tipo_peca_id',
                                    $peca->tipo_peca_id
                                ) == $tipo->id
                            )
                        >

                            {{ $tipo->nome }}

                        </option>

                    @endforeach

                </select>

            </div>


            <div class="campo campo-largo">

                <label for="descricao">
                    Descrição
                </label>

                <textarea
                    name="descricao"
                    id="descricao"
                    rows="4"
                    placeholder="Descrição geral do modelo..."
                >{{ old(
                    'descricao',
                    $peca->descricao
                ) }}</textarea>

            </div>


            <div class="campo campo-largo">

                <label for="observacoes">
                    Observações
                </label>

                <textarea
                    name="observacoes"
                    id="observacoes"
                    rows="4"
                    placeholder="Observações importantes..."
                >{{ old(
                    'observacoes',
                    $peca->observacoes
                ) }}</textarea>

            </div>


            <div class="campo-largo">

                <button
                    type="submit"
                    class="botao"
                >
                    Salvar alterações
                </button>

            </div>

        </div>

    </form>

</div>


@endsection