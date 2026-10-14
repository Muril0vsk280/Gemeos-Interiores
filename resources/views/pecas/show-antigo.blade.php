<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        {{ $peca->codigo }} - {{ $peca->nome }}
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            color: #222;
        }

        header {
            background: #1f1f1f;
            color: white;
            padding: 20px 40px;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header a {
            color: white;
            text-decoration: none;
        }

        main {
            max-width: 1200px;
            margin: auto;
            padding: 40px 20px;
        }

        .voltar {
            color: #555;
            text-decoration: none;
        }

        .cabecalho {
            margin-top: 25px;
            margin-bottom: 30px;
        }

        .codigo {
            font-weight: bold;
            color: #777;
            margin-bottom: 5px;
        }

        .cabecalho h1 {
            margin: 0 0 8px;
            font-size: 36px;
        }

        .tipo {
            color: #777;
        }

        .box {
            background: white;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 10px;

            box-shadow:
                0 4px 15px rgba(0,0,0,0.05);
        }

        .box h2 {
            margin-top: 0;
        }

        .medidas {
            display: grid;

            grid-template-columns:
                repeat(auto-fit, minmax(180px, 1fr));

            gap: 12px;
        }

        .medida {
            background: #f7f7f7;
            padding: 15px;
            border-radius: 8px;
        }

        .medida strong {
            display: block;
            margin-bottom: 5px;
        }

        .valor {
            font-size: 20px;
        }

        .etapa {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
        }

        .etapa h3 {
            margin-top: 0;
        }

        .materiais {
            margin-top: 15px;
        }

        .material {
            background: #f7f7f7;
            padding: 15px;
            border-radius: 7px;
            margin-bottom: 10px;
        }

        .fotos {
            display: grid;

            grid-template-columns:
                repeat(auto-fill, minmax(220px, 1fr));

            gap: 15px;
        }

        .foto {
            background: #eee;
            border-radius: 8px;
            overflow: hidden;
        }

        .foto img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            display: block;
        }

        .sem-dados {
            color: #888;
        }

        .form-box {
            background: #f7f7f7;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .form-box input,
        .form-box select,
        .form-box textarea {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 10px;
            font-family: inherit;
            font-size: 14px;
        }

        .form-box textarea {
            width: 100%;
            min-height: 80px;
            resize: vertical;
        }

        .botao {
            display: inline-block;
            border: none;
            background: #222;
            color: white;
            padding: 11px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
        }

        .botao-secundario {
            display: inline-block;
            background: #eee;
            color: #222;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
        }

        .botao-secundario:hover {
            background: #ddd;
        }

        .sucesso {
            background: #e5f7e8;
            color: #176b2c;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .erro {
            background: #ffe5e5;
            color: #a40000;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .historico-item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .historico-item:last-child {
            border-bottom: none;
        }

        .historico-data {
            color: #777;
        }

        @media (max-width: 800px) {
            header {
                padding: 18px 20px;
            }

            .cabecalho h1 {
                font-size: 28px;
            }

            .form-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>

</head>

<body>

<header>

    <a href="{{ route('dashboard') }}">
        <strong>
            GÊMEOS INTERIORES
        </strong>
    </a>

    <span>

        {{ auth()->user()->name }}

        @if (auth()->user()->cargo)

            —
            {{ auth()->user()->cargo->nome }}

        @endif

    </span>

</header>


<main>

    @php

        $cargoUsuario = auth()->user()->cargo?->nome;

        $podeEditar = in_array(
            $cargoUsuario,
            [
                'Administrador',
                'Encarregado'
            ],
            true
        );

    @endphp

    @if ($cargoUsuario === 'Administrador')

    <div
        style="
            margin-top:40px;
            padding:25px;
            border:1px solid #f1b7b7;
            background:#fff7f7;
            border-radius:10px;
        "
    >

        <h3
            style="
                margin-top:0;
                color:#a40000;
            "
        >
            Excluir modelo
        </h3>


        <p>
            Esta ação é permanente.
            As medidas, materiais, etapas,
            fotos e histórico deste modelo
            também serão removidos.
        </p>


        <p>
            Para confirmar, digite:
        </p>


        <p>
            <strong>
                {{ $peca->codigo }}
            </strong>
        </p>


        <form
            method="POST"
            action="{{ route(
                'pecas.destroy',
                $peca
            ) }}"
            onsubmit="
                return confirm(
                    'ATENÇÃO: deseja realmente excluir este modelo permanentemente?'
                );
            "
        >

            @csrf
            @method('DELETE')


            <input
                type="text"
                name="confirmacao_codigo"
                placeholder="Digite {{ $peca->codigo }}"
                autocomplete="off"
                required
                style=" 
                    width:100%;
                    max-width:350px;
                    padding:11px;
                    border:1px solid #ccc;
                    border-radius:6px;
                    margin-bottom:12px;
                "
            >


            <br>


            <button
                type="submit"
                style="
                    border:none;
                    background:#b42318;
                    color:white;
                    padding:11px 16px;
                    border-radius:7px;
                    cursor:pointer;
                    font-weight:bold;
                "
            >
                Excluir modelo permanentemente
            </button>

        </form>

    </div>

@endif


    {{-- MENSAGENS --}}

    @if (session('sucesso'))

        <div class="sucesso">
            {{ session('sucesso') }}
        </div>

    @endif


    @if ($errors->any())

        <div class="erro">

            <strong>
                Verifique as informações:
            </strong>

            <ul>

                @foreach ($errors->all() as $erro)

                    <li>
                        {{ $erro }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <a
        href="{{ route('pecas.index') }}"
        class="voltar"
    >
        ← Voltar para modelos
    </a>


    {{-- CABEÇALHO --}}

    <div class="cabecalho">

        <div class="codigo">
            {{ $peca->codigo }}
        </div>

        <h1>
            {{ $peca->nome }}
        </h1>

        <div class="tipo">
            {{ $peca->tipo->nome }}
        </div>


        @if ($podeEditar)

            <div style="margin-top:20px;">

                <a
                    href="{{ route('pecas.edit', $peca) }}"
                    class="botao"
                >
                    Editar modelo
                </a>

            </div>

        @endif

    </div>


    {{-- FOTOS GERAIS --}}

    <div class="box">

        <h2>
            Fotos do modelo
        </h2>


        @if ($podeEditar)

            <form
                method="POST"
                action="{{ route('pecas.fotos.store', $peca) }}"
                enctype="multipart/form-data"
                class="form-box"
            >

                @csrf

                <h3 style="margin-top:0;">
                    Adicionar fotos
                </h3>

                <input
                    type="file"
                    name="fotos[]"
                    accept="image/*"
                    multiple
                    required
                    style="
                        display:block;
                        margin-bottom:15px;
                    "
                >

                <button
                    type="submit"
                    class="botao"
                >
                    + Adicionar fotos
                </button>

            </form>

        @endif


        @php

            $fotosGerais =
                $peca->fotos
                    ->whereNull('etapa_id')
                    ->sortBy('ordem');

        @endphp


        @if ($fotosGerais->count())

            <div class="fotos">

               @foreach ($fotosGerais as $foto)

    <div class="foto">

        <img
            src="{{ asset('storage/' . $foto->caminho) }}"
            alt="{{ $foto->descricao ?? $peca->nome }}"
            loading="lazy"
        >


        @if ($podeEditar)

            <div style="
                padding:10px;
                background:white;
            ">

                <form
                    method="POST"
                    action="{{ route(
                        'pecas.fotos.destroy',
                        [$peca, $foto]
                    ) }}"
                    onsubmit="
                        return confirm(
                            'Tem certeza que deseja remover esta foto?'
                        );
                    "
                >

                    @csrf
                    @method('DELETE')


                    <button
                        type="submit"
                        style="
                            border:none;
                            background:#b42318;
                            color:white;
                            padding:8px 12px;
                            border-radius:6px;
                            cursor:pointer;
                            font-weight:bold;
                        "
                    >
                        Remover foto
                    </button>

                </form>

            </div>

        @endif

    </div>

@endforeach

            </div>

        @else

            <p class="sem-dados">
                Nenhuma foto cadastrada ainda.
            </p>

        @endif

    </div>


    {{-- INFORMAÇÕES GERAIS --}}

    <div class="box">

        <h2>
            Informações gerais
        </h2>


        @if ($peca->descricao)

            <p>
                {{ $peca->descricao }}
            </p>

        @else

            <p class="sem-dados">
                Nenhuma descrição cadastrada.
            </p>

        @endif


        @if ($peca->observacoes)

            <strong>
                Observações:
            </strong>

            <p>
                {{ $peca->observacoes }}
            </p>

        @endif


        <small>

            Cadastrado por:

            {{ $peca->criador->name ?? 'Sistema' }}

        </small>

    </div>


    {{-- ============================================================ --}}
    {{-- MEDIDAS GERAIS --}}
    {{-- ============================================================ --}}

    <div class="box">

        <h2>
            Medidas gerais
        </h2>


        @if ($podeEditar)

            <form
                method="POST"
                action="{{ route('pecas.medidas.store', $peca) }}"
                class="form-box form-grid"
                style="
                    display:grid;
                    grid-template-columns:2fr 1fr 1fr 2fr auto;
                    gap:10px;
                "
            >

                @csrf

                <input
                    type="text"
                    name="nome"
                    placeholder="Ex: Altura"
                    required
                >

                <input
                    type="number"
                    step="0.01"
                    min="0"
                    name="valor"
                    placeholder="Valor"
                >

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

                <input
                    type="text"
                    name="observacao"
                    placeholder="Observação opcional"
                >

                <button
                    type="submit"
                    class="botao"
                >
                    + Adicionar
                </button>

            </form>

        @endif


        @php

            $medidasGerais =
                $peca->medidas
                    ->whereNull('etapa_id');

        @endphp


        @if ($medidasGerais->count())

            <div class="medidas">

                @foreach ($medidasGerais as $medida)

                    <div class="medida">

                        <strong>
                            {{ $medida->nome }}
                        </strong>


                        <div class="valor">

                            {{ $medida->valor }}

                            {{ $medida->unidade }}

                        </div>


                        @if ($medida->observacao)

                            <div style="margin-top:5px;">

                                <small>
                                    {{ $medida->observacao }}
                                </small>

                            </div>

                        @endif


                        @if ($podeEditar)

                            <div style="margin-top:12px;">

                                <a
                                    href="{{ route(
                                        'pecas.medidas.edit',
                                        [$peca, $medida]
                                    ) }}"
                                    class="botao-secundario"
                                >
                                    Editar medida
                                </a>

                            </div>

                        @endif

                    </div>

                @endforeach

            </div>

        @else

            <p class="sem-dados">
                Nenhuma medida cadastrada.
            </p>

        @endif

    </div>


    {{-- ============================================================ --}}
    {{-- ETAPAS --}}
    {{-- ============================================================ --}}

    <div class="box">

        <h2>
            Etapas de produção
        </h2>


        {{-- ADICIONAR ETAPA --}}

        @if ($podeEditar && $etapasDisponiveis->count())

            <form
                method="POST"
                action="{{ route('pecas.etapas.store', $peca) }}"
                class="form-box"
            >

                @csrf


                <h3 style="margin-top:0;">
                    Adicionar etapa
                </h3>


                <div style="margin-bottom:15px;">

                    <label for="etapa_id">

                        <strong>
                            Etapa
                        </strong>

                    </label>


                    <select
                        name="etapa_id"
                        id="etapa_id"
                        required
                        style="
                            display:block;
                            width:100%;
                            padding:12px;
                            margin-top:6px;
                        "
                    >

                        <option value="">
                            Selecione uma etapa
                        </option>


                        @foreach ($etapasDisponiveis as $etapaDisponivel)

                            <option value="{{ $etapaDisponivel->id }}">

                                {{ $etapaDisponivel->nome }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <div style="margin-bottom:15px;">

                    <label for="descricao_etapa">

                        <strong>
                            Descrição
                        </strong>

                    </label>


                    <textarea
                        name="descricao"
                        id="descricao_etapa"
                        placeholder="Descreva como esta etapa funciona..."
                    ></textarea>

                </div>


                <div style="margin-bottom:15px;">

                    <label for="observacoes_etapa">

                        <strong>
                            Observações
                        </strong>

                    </label>


                    <textarea
                        name="observacoes"
                        id="observacoes_etapa"
                        placeholder="Observações importantes..."
                    ></textarea>

                </div>


                <button
                    type="submit"
                    class="botao"
                >
                    + Adicionar etapa
                </button>

            </form>

        @endif


        {{-- LISTA DAS ETAPAS --}}

        @forelse ($peca->etapas->sortBy('pivot.ordem') as $etapa)

            <div class="etapa">


                <h3>
                    {{ $etapa->nome }}
                </h3>


                @if ($podeEditar)

                    <div style="margin-bottom:15px;">

                        <a
                            href="{{ route(
                                'pecas.etapas.edit',
                                [$peca, $etapa]
                            ) }}"
                            class="botao-secundario"
                        >
                            Editar etapa
                        </a>

                    </div>

                @endif


                {{-- DESCRIÇÃO --}}

                @if ($etapa->pivot->descricao)

                    <p>
                        {{ $etapa->pivot->descricao }}
                    </p>

                @else

                    <p class="sem-dados">
                        Nenhuma descrição cadastrada.
                    </p>

                @endif


                {{-- OBSERVAÇÕES --}}

                @if ($etapa->pivot->observacoes)

                    <p>

                        <strong>
                            Observações:
                        </strong>

                        {{ $etapa->pivot->observacoes }}

                    </p>

                @endif


                {{-- ==================================================== --}}
{{-- MEDIDAS DA ETAPA --}}
{{-- ==================================================== --}}

<h4>
    Medidas da etapa
</h4>


{{-- ADICIONAR MEDIDA NA ETAPA --}}

@if ($podeEditar)

    <form
        method="POST"
        action="{{ route('pecas.medidas.store', $peca) }}"
        class="form-box form-grid"
        style="
            display:grid;
            grid-template-columns:2fr 1fr 1fr 2fr auto;
            gap:10px;
        "
    >

        @csrf


        <input
            type="hidden"
            name="etapa_id"
            value="{{ $etapa->id }}"
        >


        <input
            type="text"
            name="nome"
            placeholder="Ex: Largura do braço"
            required
        >


        <input
            type="number"
            step="0.01"
            min="0"
            name="valor"
            placeholder="Valor"
        >


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


        <input
            type="text"
            name="observacao"
            placeholder="Observação opcional"
        >


        <button
            type="submit"
            class="botao"
        >
            + Adicionar
        </button>

    </form>

@endif


{{-- LISTA DAS MEDIDAS DA ETAPA --}}

@php

    $medidasEtapa =
        $peca->medidas
            ->where(
                'etapa_id',
                $etapa->id
            );

@endphp


@if ($medidasEtapa->count())

    <div class="medidas">

        @foreach ($medidasEtapa as $medida)

            <div class="medida">

                <strong>
                    {{ $medida->nome }}
                </strong>


                <div class="valor">

                    {{ $medida->valor }}

                    {{ $medida->unidade }}

                </div>


                @if ($medida->observacao)

                    <div style="margin-top:5px;">

                        <small>
                            {{ $medida->observacao }}
                        </small>

                    </div>

                @endif


                @if ($podeEditar)

                    <div style="margin-top:12px;">

                        <a
                            href="{{ route(
                                'pecas.medidas.edit',
                                [$peca, $medida]
                            ) }}"
                            class="botao-secundario"
                        >
                            Editar medida
                        </a>

                    </div>

                @endif

            </div>

        @endforeach

    </div>

@else

    <p class="sem-dados">
        Nenhuma medida cadastrada nesta etapa.
    </p>

@endif


                @if ($medidasEtapa->count())

                    <h4>
                        Medidas
                    </h4>


                    <div class="medidas">

                        @foreach ($medidasEtapa as $medida)

                            <div class="medida">

                                <strong>
                                    {{ $medida->nome }}
                                </strong>


                                <div class="valor">

                                    {{ $medida->valor }}

                                    {{ $medida->unidade }}

                                </div>


                                @if ($medida->observacao)

                                    <div style="margin-top:5px;">

                                        <small>
                                            {{ $medida->observacao }}
                                        </small>

                                    </div>

                                @endif


                                @if ($podeEditar)

                                    <div style="margin-top:12px;">

                                        <a
                                            href="{{ route(
                                                'pecas.medidas.edit',
                                                [$peca, $medida]
                                            ) }}"
                                            class="botao-secundario"
                                        >
                                            Editar medida
                                        </a>

                                    </div>

                                @endif

                            </div>

                        @endforeach

                    </div>

                @endif


                {{-- ==================================================== --}}
                {{-- MATERIAIS --}}
                {{-- ==================================================== --}}

                <h4>
                    Materiais
                </h4>


                @if ($podeEditar)

                    <form
                        method="POST"
                        action="{{ route(
                            'pecas.materiais.store',
                            $peca
                        ) }}"
                        class="form-box"
                    >

                        @csrf


                        <input
                            type="hidden"
                            name="etapa_id"
                            value="{{ $etapa->id }}"
                        >


                        <div
                            class="form-grid"
                            style="
                                display:grid;
                                grid-template-columns:
                                    2fr 1fr 1fr 2fr auto;
                                gap:10px;
                            "
                        >

                            <input
                                type="text"
                                name="material_nome"
                                placeholder="Ex: Espuma D28"
                                required
                            >

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="quantidade"
                                placeholder="Qtd."
                            >

                            <input
                                type="text"
                                name="unidade"
                                placeholder="Unidade"
                            >

                            <input
                                type="text"
                                name="observacao"
                                placeholder="Observação"
                            >

                            <button
                                type="submit"
                                class="botao"
                            >
                                + Adicionar
                            </button>

                        </div>

                    </form>

                @endif


                @php

                    $materiaisEtapa =
                        $peca->pecaMateriais
                            ->where(
                                'etapa_id',
                                $etapa->id
                            );

                @endphp


                @if ($materiaisEtapa->count())

                    <div class="materiais">

                        @foreach ($materiaisEtapa as $item)

                            <div class="material">


                                <strong>
                                    {{ $item->material->nome }}
                                </strong>


                                @if ($item->quantidade !== null)

                                    —

                                    {{ $item->quantidade }}

                                    {{ $item->unidade }}

                                @elseif ($item->unidade)

                                    —

                                    {{ $item->unidade }}

                                @endif


                                @if ($item->observacao)

                                    <div style="margin-top:5px;">

                                        <small>
                                            {{ $item->observacao }}
                                        </small>

                                    </div>

                                @endif


                                @if ($podeEditar)

                                    <div style="margin-top:12px;">

                                        <a
                                            href="{{ route(
                                                'pecas.materiais.edit',
                                                [$peca, $item]
                                            ) }}"
                                            class="botao-secundario"
                                        >
                                            Editar material
                                        </a>

                                    </div>

                                @endif

                            </div>

                        @endforeach

                    </div>

                @else

                    <p class="sem-dados">
                        Nenhum material cadastrado nesta etapa.
                    </p>

                @endif


                {{-- ==================================================== --}}
                {{-- FOTOS DA ETAPA --}}
                {{-- ==================================================== --}}

                <h4>
                    Fotos
                </h4>


                @if ($podeEditar)

                    <form
                        method="POST"
                        action="{{ route(
                            'pecas.fotos.store',
                            $peca
                        ) }}"
                        enctype="multipart/form-data"
                        class="form-box"
                    >

                        @csrf


                        <input
                            type="hidden"
                            name="etapa_id"
                            value="{{ $etapa->id }}"
                        >


                        <div style="margin-bottom:10px;">

                            <strong>
                                Fotos de {{ $etapa->nome }}
                            </strong>

                        </div>


                        <input
                            type="file"
                            name="fotos[]"
                            accept="image/*"
                            multiple
                            required
                            style="
                                display:block;
                                margin-bottom:12px;
                            "
                        >


                        <button
                            type="submit"
                            class="botao"
                        >
                            + Adicionar fotos
                        </button>

                    </form>

                @endif


                @php

                    $fotosEtapa =
                        $peca->fotos
                            ->where(
                                'etapa_id',
                                $etapa->id
                            )
                            ->sortBy('ordem');

                @endphp


                @if ($fotosEtapa->count())

                    <div class="fotos">

                        @foreach ($fotosEtapa as $foto)

    <div class="foto">

        <img
            src="{{ asset(
                'storage/' .
                $foto->caminho
            ) }}"
            alt="{{ $foto->descricao ?? $etapa->nome }}"
            loading="lazy"
        >


        @if ($podeEditar)

            <div style="
                padding:10px;
                background:white;
            ">

                <form
                    method="POST"
                    action="{{ route(
                        'pecas.fotos.destroy',
                        [$peca, $foto]
                    ) }}"
                    onsubmit="
                        return confirm(
                            'Tem certeza que deseja remover esta foto?'
                        );
                    "
                >

                    @csrf
                    @method('DELETE')


                    <button
                        type="submit"
                        style="
                            border:none;
                            background:#b42318;
                            color:white;
                            padding:8px 12px;
                            border-radius:6px;
                            cursor:pointer;
                            font-weight:bold;
                        "
                    >
                        Remover foto
                    </button>

                </form>

            </div>

        @endif

    </div>

@endforeach

                    </div>

                @else

                    <p class="sem-dados">
                        Nenhuma foto cadastrada nesta etapa.
                    </p>

                @endif

            </div>

        @empty

            <p class="sem-dados">
                Nenhuma etapa cadastrada.
            </p>

        @endforelse

    </div>


    {{-- ============================================================ --}}
    {{-- HISTÓRICO --}}
    {{-- ============================================================ --}}

    <div class="box">

        <h2>
            Histórico
        </h2>


        @forelse ($peca->historicos as $registro)

            <div class="historico-item">

                <div>

                    <strong>
                        {{ $registro->usuario->name ?? 'Sistema' }}
                    </strong>
                    <span
    style="
        display:inline-block;
        margin-left:8px;
        padding:3px 7px;
        background:#f1f1f1;
        border-radius:5px;
        font-size:11px;
        color:#666;
    "
>
    {{ str_replace('_', ' ', $registro->acao) }}
</span>

                    <span class="historico-data">

                        —

                        {{ $registro->created_at->format('d/m/Y H:i') }}

                    </span>

                </div>


                <div
                    style="
                        margin-top:6px;
                        color:#444;
                    "
                >

                    {{ $registro->descricao }}

                </div>

            </div>

        @empty

            <p class="sem-dados">
                Nenhuma alteração registrada ainda.
            </p>

        @endforelse

    </div>

</main>

</body>

</html>