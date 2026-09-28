<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Novo Modelo - Gêmeos Interiores</title>

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
            max-width: 900px;

            margin: auto;
            padding: 40px 20px;
        }

        .voltar {
            color: #555;
            text-decoration: none;
        }

        .formulario {
            background: white;

            padding: 30px;

            margin-top: 25px;

            border-radius: 12px;

            box-shadow:
                0 4px 15px rgba(0,0,0,0.06);
        }

        .campo {
            margin-bottom: 22px;
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

            padding: 13px;

            border: 1px solid #ccc;
            border-radius: 7px;

            font-size: 16px;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .linha {
            display: grid;

            grid-template-columns: 1fr 2fr;

            gap: 20px;
        }

        .erro {
            color: #b00020;
            font-size: 14px;

            margin-top: 5px;
        }

        .erros {
            background: #ffe7e7;

            color: #a00000;

            padding: 15px;

            margin-bottom: 25px;

            border-radius: 8px;
        }

        .acoes {
            display: flex;

            justify-content: flex-end;

            gap: 10px;

            margin-top: 30px;
        }

        .cancelar {
            padding: 13px 22px;

            text-decoration: none;

            border: 1px solid #ccc;
            border-radius: 7px;

            color: #333;
        }

        button {
            border: none;

            background: #222;
            color: white;

            padding: 13px 25px;

            border-radius: 7px;

            font-size: 16px;
            font-weight: bold;

            cursor: pointer;
        }

        @media (max-width: 700px) {

            .linha {
                grid-template-columns: 1fr;
            }

            header {
                padding: 18px;
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
    </span>

</header>


<main>

    <a
        href="{{ route('pecas.index') }}"
        class="voltar"
    >
        ← Voltar para modelos
    </a>


    <h1>
        Novo modelo
    </h1>

    <p>
        Cadastre as informações principais do modelo.
        Depois será possível adicionar medidas, etapas,
        materiais e fotos.
    </p>


    <div class="formulario">


        @if ($errors->any())

            <div class="erros">

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


        <form
            method="POST"
            action="{{ route('pecas.store') }}"
        >

            @csrf


            <div class="linha">

                <div class="campo">

                    <label for="codigo">
                        Código *
                    </label>

                    <input
                        type="text"
                        id="codigo"
                        name="codigo"
                        value="{{ old('codigo') }}"
                        placeholder="Ex: CAD-002"
                        required
                    >

                    @error('codigo')

                        <div class="erro">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                <div class="campo">

                    <label for="nome">
                        Nome do modelo *
                    </label>

                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        value="{{ old('nome') }}"
                        placeholder="Ex: Cadeira Roma"
                        required
                    >

                </div>

            </div>


            <div class="campo">

                <label for="tipo_peca_id">
                    Tipo *
                </label>

                <select
                    id="tipo_peca_id"
                    name="tipo_peca_id"
                    required
                >

                    <option value="">
                        Selecione
                    </option>


                    @foreach ($tipos as $tipo)

                        <option
                            value="{{ $tipo->id }}"
                            @selected(
                                old('tipo_peca_id') == $tipo->id
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
                    id="descricao"
                    name="descricao"
                    placeholder="Descrição geral do modelo..."
                >{{ old('descricao') }}</textarea>

            </div>


            <div class="campo">

                <label for="observacoes">
                    Observações
                </label>

                <textarea
                    id="observacoes"
                    name="observacoes"
                    placeholder="Informações importantes sobre o modelo..."
                >{{ old('observacoes') }}</textarea>

            </div>


            <div class="acoes">

                <a
                    href="{{ route('pecas.index') }}"
                    class="cancelar"
                >
                    Cancelar
                </a>

                <button type="submit">
                    Salvar modelo
                </button>

            </div>

        </form>

    </div>

</main>

</body>
</html>