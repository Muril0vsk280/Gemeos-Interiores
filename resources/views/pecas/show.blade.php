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
            margin-top: 20px;
        }

        .material {
            background: #f7f7f7;

            padding: 12px;

            border-radius: 6px;

            margin-bottom: 8px;
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
    </span>

</header>


<main>

@if (session('sucesso'))

    <div
        style="
            background:#e5f7e8;
            color:#176b2c;
            padding:15px;
            border-radius:8px;
            margin-bottom:20px;
        "
    >
        {{ session('sucesso') }}
    </div>

@endif

    <a
        href="{{ route('pecas.index') }}"
        class="voltar"
    >
        ← Voltar para modelos
    </a>


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

    </div>


    <!-- FOTOS GERAIS -->

    <div class="box">

        <h2>
            Fotos do modelo
        </h2>


        @php

            $fotosGerais =
                $peca->fotos
                    ->whereNull('etapa_id');

        @endphp


        @if ($fotosGerais->count())

            <div class="fotos">

                @foreach ($fotosGerais as $foto)

                    <div class="foto">

                        <img
                            src="{{ asset('storage/' . $foto->caminho) }}"
                            alt="{{ $foto->descricao ?? $peca->nome }}"
                        >

                    </div>

                @endforeach

            </div>

        @else

            <p class="sem-dados">
                Nenhuma foto cadastrada ainda.
            </p>

        @endif

    </div>


    <!-- DESCRIÇÃO -->

    <div class="box">

        <h2>
            Informações gerais
        </h2>


        @if ($peca->descricao)

            <p>
                {{ $peca->descricao }}
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
            {{ $peca->criador->name }}
        </small>

    </div>


    <!-- MEDIDAS -->

    <div class="box">

        <h2>
            Medidas gerais
        </h2>

        <form
    method="POST"
    action="{{ route('pecas.medidas.store', $peca) }}"
    style="
        display:grid;
        grid-template-columns:2fr 1fr 1fr 2fr auto;
        gap:10px;
        margin-bottom:25px;
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
        style="
            border:none;
            background:#222;
            color:white;
            padding:12px 18px;
            border-radius:6px;
            cursor:pointer;
        "
    >
        + Adicionar
    </button>

</form>


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

                            <small>
                                {{ $medida->observacao }}
                            </small>

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


    <!-- ETAPAS -->

    <div class="box">

        <h2>
            Etapas de produção
        </h2>


        @forelse ($peca->etapas->sortBy('pivot.ordem') as $etapa)

            <div class="etapa">

                <h3>
                    {{ $etapa->nome }}
                </h3>


                @if ($etapa->pivot->descricao)

                    <p>
                        {{ $etapa->pivot->descricao }}
                    </p>

                @endif


                @if ($etapa->pivot->observacoes)

                    <p>

                        <strong>
                            Observações:
                        </strong>

                        {{ $etapa->pivot->observacoes }}

                    </p>

                @endif


                <!-- MEDIDAS DA ETAPA -->

                @php

                    $medidasEtapa =
                        $peca->medidas
                            ->where('etapa_id', $etapa->id);

                @endphp


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

                                {{ $medida->valor }}
                                {{ $medida->unidade }}

                            </div>

                        @endforeach

                    </div>

                @endif


                <!-- MATERIAIS DA ETAPA -->

                @php

                    $materiaisEtapa =
                        $peca->pecaMateriais
                            ->where('etapa_id', $etapa->id);

                @endphp


                @if ($materiaisEtapa->count())

                    <div class="materiais">

                        <h4>
                            Materiais
                        </h4>


                        @foreach ($materiaisEtapa as $item)

                            <div class="material">

                                <strong>
                                    {{ $item->material->nome }}
                                </strong>


                                @if ($item->quantidade)

                                    —
                                    {{ $item->quantidade }}

                                    {{ $item->unidade }}

                                @endif


                                @if ($item->observacao)

                                    <div>

                                        <small>
                                            {{ $item->observacao }}
                                        </small>

                                    </div>

                                @endif

                            </div>

                        @endforeach

                    </div>

                @endif


                <!-- FOTOS DA ETAPA -->

                @php

                    $fotosEtapa =
                        $peca->fotos
                            ->where('etapa_id', $etapa->id);

                @endphp


                @if ($fotosEtapa->count())

                    <h4>
                        Fotos
                    </h4>

                    <div class="fotos">

                        @foreach ($fotosEtapa as $foto)

                            <div class="foto">

                                <img
                                    src="{{ asset('storage/' . $foto->caminho) }}"
                                    alt="{{ $foto->descricao ?? $etapa->nome }}"
                                >

                            </div>

                        @endforeach

                    </div>

                @endif

            </div>

        @empty

            <p class="sem-dados">
                Nenhuma etapa cadastrada.
            </p>

        @endforelse

    </div>

</main>

</body>

</html>