<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Editar {{ $peca->codigo }}
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
            max-width: 850px;

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

        h1 {
            margin-top: 0;
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
            min-height: 110px;

            resize: vertical;
        }

        .linha {
            display: grid;

            grid-template-columns:
                1fr 2fr;

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

            font-size: 14px;
        }

        .erro {
            background: #ffe5e5;
            color: #a40000;

            padding: 15px;

            border-radius: 8px;

            margin-bottom: 20px;
        }

        @media (max-width: 700px) {

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
            Editar modelo
        </h1>

        <p style="color:#777;">

            {{ $peca->codigo }}
            —
            {{ $peca->nome }}

        </p>

        @if ($errors->any())

            <div class="erro">

                <strong>
                    Corrija os campos abaixo:
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
            action="{{ route('pecas.update', $peca) }}"
        >

            @csrf
            @method('PUT')

            <div class="linha">

                <div class="campo">

                    <label for="codigo">
                        Código
                    </label>

                    <input
                        type="text"
                        id="codigo"
                        name="codigo"
                        value="{{ old('codigo', $peca->codigo) }}"
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
                        value="{{ old('nome', $peca->nome) }}"
                        required
                    >

                </div>

            </div>

            <div class="campo">

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

            <div class="campo">

                <label for="descricao">
                    Descrição
                </label>

                <textarea
                    name="descricao"
                    id="descricao"
                    placeholder="Descrição geral do modelo..."
                >{{ old('descricao', $peca->descricao) }}</textarea>

            </div>

            <div class="campo">

                <label for="observacoes">
                    Observações
                </label>

                <textarea
                    name="observacoes"
                    id="observacoes"
                    placeholder="Observações importantes..."
                >{{ old('observacoes', $peca->observacoes) }}</textarea>

            </div>

            <button type="submit">
                Salvar alterações
            </button>

        </form>

    </div>

</main>

</body>

</html>