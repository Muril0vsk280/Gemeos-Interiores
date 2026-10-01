<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Usuários - Gêmeos Interiores
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
            max-width: 1100px;
            margin: auto;
            padding: 40px 20px;
        }

        .topo {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;

            margin-bottom: 25px;
        }

        .botao {
            display: inline-block;

            background: #222;
            color: white;

            padding: 11px 17px;

            border-radius: 7px;

            text-decoration: none;

            font-weight: bold;
        }

        .voltar {
            color: #555;
            text-decoration: none;
        }

        .sucesso {
            background: #e5f7e8;
            color: #176b2c;

            padding: 15px;

            border-radius: 8px;

            margin-bottom: 20px;
        }

        .usuario {
            background: white;

            padding: 20px;

            border-radius: 10px;

            margin-bottom: 12px;

            box-shadow:
                0 4px 15px rgba(0,0,0,0.05);

            display: flex;

            align-items: center;
            justify-content: space-between;

            gap: 20px;
        }

        .nome {
            font-weight: bold;
            font-size: 18px;
        }

        .email {
            color: #777;
            margin-top: 5px;
        }

        .cargo {
            margin-top: 6px;
        }

        .ativo {
            color: #176b2c;
            font-weight: bold;
        }

        .inativo {
            color: #a40000;
            font-weight: bold;
        }

        @media (max-width: 650px) {

            .topo,
            .usuario {
                flex-direction: column;
                align-items: flex-start;
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

    @if (session('sucesso'))

        <div class="sucesso">
            {{ session('sucesso') }}
        </div>

    @endif


    <a
        href="{{ route('dashboard') }}"
        class="voltar"
    >
        ← Voltar para o dashboard
    </a>


    <div class="topo">

        <div>

            <h1>
                Usuários
            </h1>

            <p style="color:#777;">
                Controle de acesso ao sistema.
            </p>

        </div>


        <a
            href="{{ route('usuarios.create') }}"
            class="botao"
        >
            + Novo usuário
        </a>

    </div>


    @foreach ($usuarios as $usuario)

        <div class="usuario">

            <div>

                <div class="nome">
                    {{ $usuario->name }}
                </div>

                <div class="email">
                    {{ $usuario->email }}
                </div>

                <div class="cargo">

                    {{ $usuario->cargo?->nome }}

                    —

                    @if ($usuario->active)

                        <span class="ativo">
                            Ativo
                        </span>

                    @else

                        <span class="inativo">
                            Inativo
                        </span>

                    @endif

                </div>

            </div>


            <a
                href="{{ route(
                    'usuarios.edit',
                    $usuario
                ) }}"
                class="botao"
            >
                Editar
            </a>

        </div>

    @endforeach

</main>

</body>

</html>