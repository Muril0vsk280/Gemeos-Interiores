<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Editar medida
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
        }

        header a {
            color: white;
            text-decoration: none;
        }

        main {
            max-width: 800px;

            margin: auto;

            padding: 40px 20px;
        }

        .voltar {
            display: inline-block;

            margin-bottom: 25px;

            color: #555;
            text-decoration: none;
        }

        .box {
            background: white;

            padding: 30px;

            border-radius: 10px;

            box-shadow:
                0 4px 15px rgba(0,0,0,0.05);
        }

        .info {
            background: #f7f7f7;

            padding: 15px;

            border-radius: 7px;

            margin-bottom: 25px;
        }

        .campo {
            margin-bottom: 20px;
        }

        label {
            display: block;

            font-weight: bold;

            margin-bottom: 7px;
        }

        input,
        select,
        textarea {
            width: 100%;

            padding: 12px;

            border: 1px solid #ccc;

            border-radius: 7px;

            font-size: 15px;

            font-family: Arial, sans-serif;
        }

        textarea {
            min-height: 100px;

            resize: vertical;
        }

        .linha {
            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 15px;
        }

        button {
            border: none;

            background: #222;
            color: white;

            padding: 13px 22px;

            border-radius: 7px;

            cursor: pointer;

            font-weight: bold;
        }

        .erro {
            background: #ffe5e5;
            color: #a40000;

            padding: 15px;

            border-radius: 8px;

            margin-bottom: 20px;
        }

        @media (max-width: 650px) {

            .linha {
                grid-template-columns: 1fr;
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

</header>


<main>

    <a
        href="{{ route('pecas.show', $peca) }}"
        class="voltar"
    >
        ← Voltar para o modelo
    </a>


    <div class="box">

        <h1>
            Editar medida
        </h1>


        <div class="info">

            <strong>
                {{ $medida->nome }}
            </strong>

            <br><br>

            Modelo:

            {{ $peca->codigo }}
            —
            {{ $peca->nome }}


            @if ($medida->etapa)

                <br>

                Etapa:

                {{ $medida->etapa->nome }}

            @else

                <br>

                Tipo:

                Medida geral do modelo

            @endif

        </div>


        @if ($errors->any())

            <div class="erro">

                <strong>
                    Corrija os campos:
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


        <form
            method="POST"
            action="{{ route(
                'pecas.medidas.update',
                [$peca, $medida]
            ) }}"
        >

            @csrf
            @method('PUT')


            <div class="campo">

                <label for="nome">
                    Nome da medida
                </label>

                <input
                    type="text"
                    name="nome"
                    id="nome"
                    value="{{ old(
                        'nome',
                        $medida->nome
                    ) }}"
                    required
                >

            </div>


            <div class="linha">

                <div class="campo">

                    <label for="valor">
                        Valor
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="valor"
                        id="valor"
                        value="{{ old(
                            'valor',
                            $medida->valor
                        ) }}"
                    >

                </div>


                <div class="campo">

                    <label for="unidade">
                        Unidade
                    </label>

                    <select
                        name="unidade"
                        id="unidade"
                    >

                        <option
                            value="cm"
                            @selected(
                                old(
                                    'unidade',
                                    $medida->unidade
                                ) === 'cm'
                            )
                        >
                            cm
                        </option>

                        <option
                            value="mm"
                            @selected(
                                old(
                                    'unidade',
                                    $medida->unidade
                                ) === 'mm'
                            )
                        >
                            mm
                        </option>

                        <option
                            value="m"
                            @selected(
                                old(
                                    'unidade',
                                    $medida->unidade
                                ) === 'm'
                            )
                        >
                            m
                        </option>

                    </select>

                </div>

            </div>


            <div class="campo">

                <label for="observacao">
                    Observação
                </label>

                <textarea
                    name="observacao"
                    id="observacao"
                    placeholder="Observação opcional..."
                >{{ old(
                    'observacao',
                    $medida->observacao
                ) }}</textarea>

            </div>


            <button type="submit">
                Salvar alterações
            </button>

        </form>

    </div>

</main>

</body>

</html>