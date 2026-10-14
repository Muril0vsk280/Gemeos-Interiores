@extends('layouts.app')

@section('title', $peca->nome . ' - Gêmeos Interiores')


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

    $administrador =
        $cargoUsuario === 'Administrador';


    /*
    |--------------------------------------------------------------------------
    | FOTOS GERAIS
    |--------------------------------------------------------------------------
    */

    $fotosGerais =
        $peca->fotos
            ->whereNull('etapa_id');


    /*
    |--------------------------------------------------------------------------
    | FOTO PRINCIPAL
    |--------------------------------------------------------------------------
    |
    | A foto principal deve ser uma foto geral,
    | nunca uma foto de uma etapa.
    |
    */

    $fotoPrincipal =
        $fotosGerais->first();


    /*
    |--------------------------------------------------------------------------
    | MEDIDAS GERAIS
    |--------------------------------------------------------------------------
    */

    $medidasGerais =
        $peca->medidas
            ->whereNull('etapa_id');

@endphp


@section('content')


{{-- ============================================================ --}}
{{-- VOLTAR --}}
{{-- ============================================================ --}}

<div style="margin-bottom:20px;">

    <a
        href="{{ route('pecas.index') }}"
        class="botao botao-secundario"
    >
        ← Voltar aos modelos
    </a>

</div>


{{-- ============================================================ --}}
{{-- CABEÇALHO DO MODELO --}}
{{-- ============================================================ --}}

<div class="modelo-cabecalho">


    {{-- FOTO PRINCIPAL --}}

    <div class="modelo-foto-principal">

        @if ($fotoPrincipal)

            <img
                src="{{ asset(
                    'storage/' .
                    $fotoPrincipal->caminho
                ) }}"
                alt="{{ $peca->nome }}"
            >

        @else

            <div class="modelo-sem-foto">

                Nenhuma foto cadastrada

            </div>

        @endif

    </div>


    {{-- INFORMAÇÕES --}}

    <div class="modelo-informacoes">

        <h1>
            {{ $peca->nome }}
        </h1>


        <div class="modelo-identificacao">

            {{ $peca->codigo }}

            @if ($peca->tipo)

                • {{ $peca->tipo->nome }}

            @endif

        </div>


        @if ($peca->descricao)

            <div class="modelo-descricao">

                <strong>
                    Sobre este modelo
                </strong>

                <p>
                    {{ $peca->descricao }}
                </p>

            </div>

        @endif


        @if ($peca->observacoes)

            <div class="modelo-descricao">

                <strong>
                    Observações
                </strong>

                <p>
                    {{ $peca->observacoes }}
                </p>

            </div>

        @endif


        @if ($podeEditar)

            <a
                href="{{ route(
                    'pecas.edit',
                    $peca
                ) }}"
                class="botao"
            >
                Editar modelo
            </a>

        @endif

    </div>

</div>


{{-- ============================================================ --}}
{{-- ATALHOS --}}
{{-- ============================================================ --}}

<div class="atalhos-modelo">

    <a
        href="#fotos"
        class="atalho-modelo"
    >

        <span class="atalho-icone">
            📷
        </span>

        Fotos

    </a>


    <a
        href="#medidas"
        class="atalho-modelo"
    >

        <span class="atalho-icone">
            📏
        </span>

        Medidas

    </a>


    <a
        href="#materiais"
        class="atalho-modelo"
    >

        <span class="atalho-icone">
            📦
        </span>

        Materiais

    </a>


    <a
        href="#fabricacao"
        class="atalho-modelo"
    >

        <span class="atalho-icone">
            🛠️
        </span>

        Como fabricar

    </a>

</div>


{{-- ============================================================ --}}
{{-- FOTOS --}}
{{-- ============================================================ --}}

<section
    id="fotos"
    class="secao-modelo"
>

    <div class="secao-modelo-titulo">

        <h2>
            Fotos
        </h2>

        <p>
            Imagens gerais deste modelo.
        </p>

    </div>


    <div class="card">

        @if ($fotosGerais->count())

            <div class="galeria-fotos">

                @foreach ($fotosGerais as $foto)

                    <div class="foto-miniatura">

                        <img
                            src="{{ asset(
                        'storage/' .
                        $foto->caminho) }}"
                        alt="{{ $peca->nome }}"
                        class="foto-ampliavel"
                        onclick="abrirFoto(this.src)"
                    >


                        @if ($podeEditar)

                            <div class="foto-acoes">

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'pecas.fotos.destroy',
                                        [
                                            $peca,
                                            $foto
                                        ]
                                    ) }}"
                                    onsubmit="
                                        return confirm(
                                            'Remover esta foto?'
                                        );
                                    "
                                >

                                    @csrf
                                    @method('DELETE')


                                    <button
                                        type="submit"
                                        class="botao botao-perigo"
                                    >
                                        Remover
                                    </button>

                                </form>

                            </div>

                        @endif

                    </div>

                @endforeach

            </div>

        @else

            <div class="sem-informacao">

                Nenhuma foto cadastrada.

            </div>

        @endif


        @if ($podeEditar)

            <details class="container-expansivel"
                     style="margin-top:20px; box-shadow:none;">

                <summary>
                    Adicionar fotos
                </summary>


                <div class="container-conteudo">

                    <form
                        method="POST"
                        action="{{ route(
                            'pecas.fotos.store',
                            $peca
                        ) }}"
                        enctype="multipart/form-data"
                    >

                        @csrf


                        <div class="campo">

                            <label>
                                Escolha as fotos
                            </label>

                            <input
                                type="file"
                                name="fotos[]"
                                accept="image/*"
                                multiple
                                required
                            >

                        </div>


                        <button
                            type="submit"
                            class="botao"
                        >
                            Adicionar fotos
                        </button>

                    </form>

                </div>

            </details>

        @endif

    </div>

</section>


{{-- ============================================================ --}}
{{-- MEDIDAS GERAIS --}}
{{-- ============================================================ --}}

<section
    id="medidas"
    class="secao-modelo"
>

    <div class="secao-modelo-titulo">

        <h2>
            Medidas gerais
        </h2>

        <p>
            Principais medidas do modelo.
        </p>

    </div>


    <div class="card">

        @if ($medidasGerais->count())

            <div class="grade-informacoes">

                @foreach ($medidasGerais as $medida)

                    <div class="info-card">

                        <strong>
                            {{ $medida->nome }}
                        </strong>


                        <div class="info-valor">

                            {{ $medida->valor }}

                            {{ $medida->unidade }}

                        </div>


                        @if ($medida->observacao)

                            <small>
                                {{ $medida->observacao }}
                            </small>

                        @endif


                        @if ($podeEditar)

                            <div style="margin-top:10px;">

                                <a
                                    href="{{ route(
                                        'pecas.medidas.edit',
                                        [
                                            $peca,
                                            $medida
                                        ]
                                    ) }}"
                                    class="botao botao-secundario"
                                >
                                    Editar
                                </a>

                            </div>

                        @endif

                    </div>

                @endforeach

            </div>

        @else

            <div class="sem-informacao">

                Nenhuma medida geral cadastrada.

            </div>

        @endif


        @if ($podeEditar)

            <details
                class="container-expansivel"
                style="margin-top:20px; box-shadow:none;"
            >

                <summary>
                    Adicionar medida
                </summary>


                <div class="container-conteudo">

                    <form
                        method="POST"
                        action="{{ route(
                            'pecas.medidas.store',
                            $peca
                        ) }}"
                        class="form-grid"
                    >

                        @csrf


                        <div class="campo">

                            <label>
                                Nome
                            </label>

                            <input
                                type="text"
                                name="nome"
                                placeholder="Ex: Altura"
                                required
                            >

                        </div>


                        <div class="campo">

                            <label>
                                Valor
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="valor"
                            >

                        </div>


                        <div class="campo">

                            <label>
                                Unidade
                            </label>

                            <select name="unidade">

                                <option value="cm">
                                    cm
                                </option>

                                <option value="mm">
                                    mm
                                </option>

                                <option value="m">
                                    m
                                </option>

                            </select>

                        </div>


                        <div class="campo">

                            <label>
                                Observação
                            </label>

                            <input
                                type="text"
                                name="observacao"
                                placeholder="Opcional"
                            >

                        </div>


                        <div class="campo-largo">

                            <button
                                type="submit"
                                class="botao"
                            >
                                Adicionar medida
                            </button>

                        </div>

                    </form>

                </div>

            </details>

        @endif

    </div>

</section>


{{-- ============================================================ --}}
{{-- MATERIAIS --}}
{{-- ============================================================ --}}

<section
    id="materiais"
    class="secao-modelo"
>

    <div class="secao-modelo-titulo">

        <h2>
            Materiais
        </h2>

        <p>
            Materiais utilizados nas etapas deste modelo.
        </p>

    </div>


    <div class="card">

        @if ($peca->pecaMateriais->count())

            @foreach ($peca->pecaMateriais as $pecaMaterial)

                <div class="material-item">

                    <div class="material-info">

                        <strong>
                            {{ $pecaMaterial->material?->nome }}
                        </strong>


                        <small>

                            @if ($pecaMaterial->etapa)

                                {{ $pecaMaterial->etapa->nome }}

                                •

                            @endif

                            {{ $pecaMaterial->quantidade }}

                            {{ $pecaMaterial->unidade }}

                        </small>


                        @if ($pecaMaterial->observacao)

                            <div>
                                {{ $pecaMaterial->observacao }}
                            </div>

                        @endif

                    </div>


                    @if ($podeEditar)

                        <a
                            href="{{ route(
                                'pecas.materiais.edit',
                                [
                                    $peca,
                                    $pecaMaterial
                                ]
                            ) }}"
                            class="botao botao-secundario"
                        >
                            Editar
                        </a>

                    @endif

                </div>

            @endforeach

        @else

            <div class="sem-informacao">

                Nenhum material cadastrado.

            </div>

        @endif

    </div>

</section>


{{-- ============================================================ --}}
{{-- COMO FABRICAR --}}
{{-- ============================================================ --}}

<section
    id="fabricacao"
    class="secao-modelo"
>

    <div class="secao-modelo-titulo">

        <h2>
            Como fabricar
        </h2>

        <p>
            Abra apenas a etapa que você precisa consultar.
        </p>

    </div>


    @forelse (
        $peca->etapas->sortBy(
            fn ($etapa) =>
                $etapa->pivot->ordem
                ?? $etapa->ordem
                ?? 0
        )
        as $etapa
    )

        @php

            $medidasEtapa =
                $peca->medidas
                    ->where(
                        'etapa_id',
                        $etapa->id
                    );


            $materiaisEtapa =
                $peca->pecaMateriais
                    ->where(
                        'etapa_id',
                        $etapa->id
                    );


            $fotosEtapa =
                $peca->fotos
                    ->where(
                        'etapa_id',
                        $etapa->id
                    );

        @endphp


        <details class="container-expansivel">

            <summary>

                {{ $etapa->nome }}

            </summary>


            <div class="container-conteudo">


                {{-- DESCRIÇÃO --}}

                @if ($etapa->pivot->descricao)

                    <h4>
                        O que fazer
                    </h4>

                    <p>
                        {{ $etapa->pivot->descricao }}
                    </p>

                @endif


                @if ($etapa->pivot->observacoes)

                    <div class="modelo-descricao">

                        <strong>
                            Atenção
                        </strong>

                        <p>
                            {{ $etapa->pivot->observacoes }}
                        </p>

                    </div>

                @endif


                @if ($podeEditar)

                    <a
                        href="{{ route(
                            'pecas.etapas.edit',
                            [
                                $peca,
                                $etapa
                            ]
                        ) }}"
                        class="botao botao-secundario"
                    >
                        Editar etapa
                    </a>

                @endif


                {{-- MEDIDAS DA ETAPA --}}

                <h4 style="margin-top:25px;">
                    Medidas
                </h4>


                @if ($medidasEtapa->count())

                    <div class="grade-informacoes">

                        @foreach ($medidasEtapa as $medida)

                            <div class="info-card">

                                <strong>
                                    {{ $medida->nome }}
                                </strong>


                                <div class="info-valor">

                                    {{ $medida->valor }}

                                    {{ $medida->unidade }}

                                </div>


                                @if ($medida->observacao)

                                    <small>
                                        {{ $medida->observacao }}
                                    </small>

                                @endif


                                @if ($podeEditar)

                                    <div style="margin-top:10px;">

                                        <a
                                            href="{{ route(
                                                'pecas.medidas.edit',
                                                [
                                                    $peca,
                                                    $medida
                                                ]
                                            ) }}"
                                            class="botao botao-secundario"
                                        >
                                            Editar
                                        </a>

                                    </div>

                                @endif

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="sem-informacao">

                        Nenhuma medida nesta etapa.

                    </div>

                @endif


                {{-- ADICIONAR MEDIDA --}}

                @if ($podeEditar)

                    <details
                        class="container-expansivel"
                        style="
                            margin-top:15px;
                            box-shadow:none;
                        "
                    >

                        <summary>
                            Adicionar medida
                        </summary>


                        <div class="container-conteudo">

                            <form
                                method="POST"
                                action="{{ route(
                                    'pecas.medidas.store',
                                    $peca
                                ) }}"
                                class="form-grid"
                            >

                                @csrf


                                <input
                                    type="hidden"
                                    name="etapa_id"
                                    value="{{ $etapa->id }}"
                                >


                                <div class="campo">

                                    <label>
                                        Nome
                                    </label>

                                    <input
                                        type="text"
                                        name="nome"
                                        required
                                    >

                                </div>


                                <div class="campo">

                                    <label>
                                        Valor
                                    </label>

                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        name="valor"
                                    >

                                </div>


                                <div class="campo">

                                    <label>
                                        Unidade
                                    </label>

                                    <select name="unidade">

                                        <option value="cm">
                                            cm
                                        </option>

                                        <option value="mm">
                                            mm
                                        </option>

                                        <option value="m">
                                            m
                                        </option>

                                    </select>

                                </div>


                                <div class="campo">

                                    <label>
                                        Observação
                                    </label>

                                    <input
                                        type="text"
                                        name="observacao"
                                    >

                                </div>


                                <div class="campo-largo">

                                    <button
                                        type="submit"
                                        class="botao"
                                    >
                                        Adicionar
                                    </button>

                                </div>

                            </form>

                        </div>

                    </details>

                @endif


                {{-- MATERIAIS --}}

                <h4 style="margin-top:25px;">
                    Materiais
                </h4>


                @if ($materiaisEtapa->count())

                    @foreach ($materiaisEtapa as $pecaMaterial)

                        <div class="material-item">

                            <div class="material-info">

                                <strong>
                                    {{ $pecaMaterial->material?->nome }}
                                </strong>


                                <small>

                                    {{ $pecaMaterial->quantidade }}

                                    {{ $pecaMaterial->unidade }}

                                </small>


                                @if ($pecaMaterial->observacao)

                                    <div>
                                        {{ $pecaMaterial->observacao }}
                                    </div>

                                @endif

                            </div>


                            @if ($podeEditar)

                                <a
                                    href="{{ route(
                                        'pecas.materiais.edit',
                                        [
                                            $peca,
                                            $pecaMaterial
                                        ]
                                    ) }}"
                                    class="botao botao-secundario"
                                >
                                    Editar
                                </a>

                            @endif

                        </div>

                    @endforeach

                @else

                    <div class="sem-informacao">

                        Nenhum material nesta etapa.

                    </div>

                @endif


                {{-- ADICIONAR MATERIAL --}}

                @if ($podeEditar)

                    <details
                        class="container-expansivel"
                        style="
                            margin-top:15px;
                            box-shadow:none;
                        "
                    >

                        <summary>
                            Adicionar material
                        </summary>


                        <div class="container-conteudo">

                            <form
                                method="POST"
                                action="{{ route(
                                    'pecas.materiais.store',
                                    $peca
                                ) }}"
                                class="form-grid"
                            >

                                @csrf


                                <input
                                    type="hidden"
                                    name="etapa_id"
                                    value="{{ $etapa->id }}"
                                >


                                <div class="campo">

                                    <label>
                                        Material
                                    </label>

                                    <input
                                        type="text"
                                        name="material_nome"
                                        required
                                    >

                                </div>


                                <div class="campo">

                                    <label>
                                        Quantidade
                                    </label>

                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        name="quantidade"
                                    >

                                </div>


                                <div class="campo">

                                    <label>
                                        Unidade
                                    </label>

                                    <input
                                        type="text"
                                        name="unidade"
                                        placeholder="Ex: un, cm, kg"
                                    >

                                </div>


                                <div class="campo">

                                    <label>
                                        Observação
                                    </label>

                                    <input
                                        type="text"
                                        name="observacao"
                                    >

                                </div>


                                <div class="campo-largo">

                                    <button
                                        type="submit"
                                        class="botao"
                                    >
                                        Adicionar material
                                    </button>

                                </div>

                            </form>

                        </div>

                    </details>

                @endif


                {{-- FOTOS DA ETAPA --}}

                <h4 style="margin-top:25px;">
                    Fotos da etapa
                </h4>


                @if ($fotosEtapa->count())

                    <div class="galeria-fotos">

                        @foreach ($fotosEtapa as $foto)

                            <div class="foto-miniatura">

                                <img
                                 src="{{ asset(
                                'storage/' .
                                 $foto->caminho) }}"
                                        alt="{{ $etapa->nome }}"
                                    class="foto-ampliavel"
                                     onclick="abrirFoto(this.src)"
>


                                @if ($podeEditar)

                                    <div class="foto-acoes">

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'pecas.fotos.destroy',
                                                [
                                                    $peca,
                                                    $foto
                                                ]
                                            ) }}"
                                            onsubmit="
                                                return confirm(
                                                    'Remover esta foto?'
                                                );
                                            "
                                        >

                                            @csrf
                                            @method('DELETE')


                                            <button
                                                type="submit"
                                                class="botao botao-perigo"
                                            >
                                                Remover
                                            </button>

                                        </form>

                                    </div>

                                @endif

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="sem-informacao">

                        Nenhuma foto nesta etapa.

                    </div>

                @endif


                {{-- ADICIONAR FOTO ETAPA --}}

                @if ($podeEditar)

                    <details
                        class="container-expansivel"
                        style="
                            margin-top:15px;
                            box-shadow:none;
                        "
                    >

                        <summary>
                            Adicionar fotos
                        </summary>


                        <div class="container-conteudo">

                            <form
                                method="POST"
                                action="{{ route(
                                    'pecas.fotos.store',
                                    $peca
                                ) }}"
                                enctype="multipart/form-data"
                            >

                                @csrf


                                <input
                                    type="hidden"
                                    name="etapa_id"
                                    value="{{ $etapa->id }}"
                                >


                                <div class="campo">

                                    <input
                                        type="file"
                                        name="fotos[]"
                                        accept="image/*"
                                        multiple
                                        required
                                    >

                                </div>


                                <button
                                    type="submit"
                                    class="botao"
                                >
                                    Adicionar fotos
                                </button>

                            </form>

                        </div>

                    </details>

                @endif

            </div>

        </details>


    @empty

        <div class="card">

            <div class="sem-informacao">

                Nenhuma etapa cadastrada neste modelo.

            </div>

        </div>

    @endforelse


    {{-- ADICIONAR ETAPA --}}

    @if (
        $podeEditar &&
        $etapasDisponiveis->count()
    )

        <details
            class="container-expansivel"
            style="margin-top:20px;"
        >

            <summary>
                + Adicionar etapa
            </summary>


            <div class="container-conteudo">

                <form
                    method="POST"
                    action="{{ route(
                        'pecas.etapas.store',
                        $peca
                    ) }}"
                >

                    @csrf


                    <div class="campo">

                        <label>
                            Etapa
                        </label>

                        <select
                            name="etapa_id"
                            required
                        >

                            <option value="">
                                Selecione
                            </option>


                            @foreach (
                                $etapasDisponiveis
                                as $etapaDisponivel
                            )

                                <option
                                    value="{{ $etapaDisponivel->id }}"
                                >
                                    {{ $etapaDisponivel->nome }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="campo">

                        <label>
                            Descrição
                        </label>

                        <textarea
                            name="descricao"
                            rows="3"
                        ></textarea>

                    </div>


                    <div class="campo">

                        <label>
                            Observações
                        </label>

                        <textarea
                            name="observacoes"
                            rows="3"
                        ></textarea>

                    </div>


                    <button
                        type="submit"
                        class="botao"
                    >
                        Adicionar etapa
                    </button>

                </form>

            </div>

        </details>

    @endif

</section>


{{-- ============================================================ --}}
{{-- HISTÓRICO --}}
{{-- ============================================================ --}}

@if ($podeEditar)

    <section class="area-administrativa">

        <details class="container-expansivel">

            <summary>
                Últimas alterações
            </summary>


            <div class="container-conteudo">

                @forelse (
                    $peca->historicos
                    as $registro
                )

                    <div class="material-item">

                        <div class="material-info">

                            <strong>

                                {{ $registro->usuario?->name
                                    ?? 'Sistema' }}

                            </strong>


                            <small>

                                {{ $registro->created_at
                                    ->format(
                                        'd/m/Y H:i'
                                    ) }}

                            </small>


                            <div>

                                {{ $registro->descricao }}

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="sem-informacao">

                        Nenhuma alteração registrada.

                    </div>

                @endforelse

            </div>

        </details>

    </section>

@endif


{{-- ============================================================ --}}
{{-- EXCLUSÃO SOMENTE ADMINISTRADOR --}}
{{-- ============================================================ --}}

@if ($administrador)

    <div class="zona-perigo">

        <details>

            <summary
                style="
                    cursor:pointer;
                    font-weight:700;
                    color:#b42318;
                "
            >
                Opções avançadas
            </summary>


            <div style="margin-top:20px;">

                <h3>
                    Excluir modelo
                </h3>


                <p>

                    Esta ação remove permanentemente
                    este modelo e todas as informações
                    relacionadas.

                </p>


                <p>
                    Digite
                    <strong>
                        {{ $peca->codigo }}
                    </strong>
                    para confirmar.
                </p>


                <form
                    method="POST"
                    action="{{ route(
                        'pecas.destroy',
                        $peca
                    ) }}"
                    onsubmit="
                        return confirm(
                            'Tem certeza que deseja excluir este modelo?'
                        );
                    "
                >

                    @csrf
                    @method('DELETE')


                    <div class="campo">

                        <input
                            type="text"
                            name="confirmacao_codigo"
                            required
                        >

                    </div>


                    <button
                        type="submit"
                        class="botao botao-perigo"
                    >
                        Excluir modelo
                    </button>

                </form>

            </div>

        </details>

    </div>

@endif
{{-- ============================================================ --}}
{{-- MODAL DE FOTO --}}
{{-- ============================================================ --}}

<div
    id="modalFoto"
    class="modal-foto"
    onclick="fecharFotoFundo(event)"
>

    <div class="modal-foto-conteudo">

        <button
            type="button"
            class="modal-foto-fechar"
            onclick="fecharFoto()"
            aria-label="Fechar imagem"
        >
            ×
        </button>


        <img
            id="modalFotoImagem"
            class="modal-foto-imagem"
            src=""
            alt="Foto ampliada"
        >

    </div>

</div>


@push('scripts')

<script>

    function abrirFoto(src) {

        const modal =
            document.getElementById('modalFoto');

        const imagem =
            document.getElementById(
                'modalFotoImagem'
            );

        imagem.src = src;

        modal.classList.add('ativo');

        document.body.classList.add(
            'modal-aberto'
        );
    }


    function fecharFoto() {

        const modal =
            document.getElementById('modalFoto');

        const imagem =
            document.getElementById(
                'modalFotoImagem'
            );

        modal.classList.remove('ativo');

        document.body.classList.remove(
            'modal-aberto'
        );

        imagem.src = '';
    }


    function fecharFotoFundo(event) {

        if (
            event.target.id === 'modalFoto'
        ) {

            fecharFoto();

        }

    }


    document.addEventListener(
        'keydown',
        function (event) {

            if (event.key === 'Escape') {

                fecharFoto();

            }

        }
    );

</script>

@endpush

@endsection