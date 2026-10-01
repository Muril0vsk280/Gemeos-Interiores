<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Editar {{ $etapa->nome }}
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

        .modelo {
            color: #777;
            margin-bottom: 30px;
        }

        .campo {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 7px;
        }

        textarea {
            width: 100%;
            min-height: 120px;

            padding: 12px;

            border: 1px solid #ccc;
            border-radius: 7px;

            font-size: 15px;
            font-family: Arial, sans-serif;

            resize: vertical;
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
            Editar etapa: {{ $etapa->nome }}
        </h1>


        <div class="modelo">

            {{ $peca->codigo }}
            —
            {{ $peca->nome }}

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
                'pecas.etapas.update',
                [$peca, $etapa]
            ) }}"
        >

            @csrf
            @method('PUT')


            <div class="campo">

                <label for="descricao">
                    Descrição
                </label>

                <textarea
                    name="descricao"
                    id="descricao"
                    placeholder="Descreva como essa etapa funciona..."
                >{{ old('descricao', $pecaEtapa->descricao) }}</textarea>

            </div>


            <div class="campo">

                <label for="observacoes">
                    Observações
                </label>

                <textarea
                    name="observacoes"
                    id="observacoes"
                    placeholder="Observações importantes..."
                >{{ old('observacoes', $pecaEtapa->observacoes) }}</textarea>

            </div>


            <button type="submit">
                Salvar alterações
            </button>

        </form>

    </div>

</main>

</body>

</html>