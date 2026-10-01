<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Editar usuário
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

        .aviso {
            background: #f7f7f7;

            padding: 12px;

            border-radius: 7px;

            color: #666;

            margin-bottom: 20px;
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
            Editar usuário
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
            action="{{ route(
                'usuarios.update',
                $usuario
            ) }}"
        >

            @csrf
            @method('PUT')


            <div class="campo">

                <label>
                    Nome
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old(
                        'name',
                        $usuario->name
                    ) }}"
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
                    value="{{ old(
                        'email',
                        $usuario->email
                    ) }}"
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

                    @foreach ($cargos as $cargo)

                        <option
                            value="{{ $cargo->id }}"
                            @selected(
                                old(
                                    'cargo_id',
                                    $usuario->cargo_id
                                ) == $cargo->id
                            )
                        >

                            {{ $cargo->nome }}

                        </option>

                    @endforeach

                </select>

            </div>


            <div class="campo">

                <label>
                    Status
                </label>

                <select
                    name="active"
                    required
                >

                    <option
                        value="1"
                        @selected(
                            old(
                                'active',
                                $usuario->active
                            ) == 1
                        )
                    >
                        Ativo
                    </option>


                    <option
                        value="0"
                        @selected(
                            old(
                                'active',
                                $usuario->active
                            ) == 0
                        )
                    >
                        Inativo
                    </option>

                </select>

            </div>


            <div class="aviso">

                Deixe a senha em branco caso
                não queira alterá-la.

            </div>


            <div class="campo">

                <label>
                    Nova senha
                </label>

                <input
                    type="password"
                    name="password"
                >

            </div>


            <div class="campo">

                <label>
                    Confirmar nova senha
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                >

            </div>


            <button type="submit">
                Salvar alterações
            </button>

        </form>

    </div>

    @if (auth()->id() !== $usuario->id)

    <div
        style="
            margin-top:30px;
            background:#fff7f7;
            border:1px solid #efb4b4;
            border-radius:10px;
            padding:25px;
        "
    >

        <h2
            style="
                margin-top:0;
                color:#a40000;
            "
        >
            Excluir usuário
        </h2>


        <p>
            Esta ação exclui permanentemente
            a conta caso ela ainda não possua
            registros no sistema.
        </p>


        <p>
            Se o usuário já possui histórico,
            apenas a desativação será permitida.
        </p>


        <p>
            Para confirmar, digite:
        </p>


        <strong>
            {{ $usuario->email }}
        </strong>


        <form
            method="POST"
            action="{{ route(
                'usuarios.destroy',
                $usuario
            ) }}"
            style="margin-top:15px;"
            onsubmit="
                return confirm(
                    'Tem certeza que deseja excluir este usuário permanentemente?'
                );
            "
        >

            @csrf
            @method('DELETE')


            <input
                type="text"
                name="confirmacao_email"
                placeholder="Digite o e-mail do usuário"
                autocomplete="off"
                required
                style="
                    width:100%;
                    max-width:450px;
                    padding:12px;
                    border:1px solid #ccc;
                    border-radius:7px;
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
                    padding:12px 18px;
                    border-radius:7px;
                    font-weight:bold;
                    cursor:pointer;
                "
            >
                Excluir usuário permanentemente
            </button>

        </form>

    </div>

@endif

</main>

</body>

</html>