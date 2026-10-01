<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Novo usuário
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        main {
            max-width: 750px;
            margin: auto;
            padding: 40px 20px;
        }

        .box {
            background: white;
            padding: 30px;
            border-radius: 10px;
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
        select {
            width: 100%;
            padding: 12px;

            border: 1px solid #ccc;
            border-radius: 7px;
        }

        button {
            border: none;

            background: #222;
            color: white;

            padding: 13px 20px;

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

        .voltar {
            display: inline-block;
            margin-bottom: 20px;

            color: #555;
            text-decoration: none;
        }

    </style>

</head>

<body>

<main>

    <a
        href="{{ route('usuarios.index') }}"
        class="voltar"
    >
        ← Voltar para usuários
    </a>


    <div class="box">

        <h1>
            Novo usuário
        </h1>


        @if ($errors->any())

            <div class="erro">

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
            action="{{ route('usuarios.store') }}"
        >

            @csrf


            <div class="campo">

                <label>
                    Nome
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                >

            </div>


            <div class="campo">

                <label>
                    E-mail
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                >

            </div>


            <div class="campo">

                <label>
                    Cargo
                </label>

                <select
                    name="cargo_id"
                    required
                >

                    <option value="">
                        Selecione
                    </option>


                    @foreach ($cargos as $cargo)

                        <option
                            value="{{ $cargo->id }}"
                            @selected(
                                old('cargo_id') ==
                                $cargo->id
                            )
                        >

                            {{ $cargo->nome }}

                        </option>

                    @endforeach

                </select>

            </div>


            <div class="campo">

                <label>
                    Senha
                </label>

                <input
                    type="password"
                    name="password"
                    required
                >

            </div>


            <div class="campo">

                <label>
                    Confirmar senha
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    required
                >

            </div>


            <button type="submit">
                Criar usuário
            </button>

        </form>

    </div>

</main>

</body>

</html>