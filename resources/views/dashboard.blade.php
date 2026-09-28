<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Gêmeos Interiores</title>

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

        main {
            max-width: 1200px;
            margin: auto;
            padding: 40px 20px;
        }

        .pesquisa {
            display: flex;
            margin: 30px 0;
        }

        .pesquisa input {
            flex: 1;
            padding: 15px;

            border: 1px solid #ccc;
            border-radius: 8px 0 0 8px;

            font-size: 16px;
        }

        .pesquisa button {
            padding: 15px 25px;

            border: none;
            background: #222;
            color: white;

            border-radius: 0 8px 8px 0;

            cursor: pointer;
        }

        .cards {
            display: grid;

            grid-template-columns:
                repeat(auto-fit, minmax(200px, 1fr));

            gap: 20px;

            margin: 30px 0;
        }

        .card {
            background: white;

            padding: 25px;

            border-radius: 10px;

            box-shadow:
                0 4px 15px rgba(0,0,0,0.08);
        }

        .card h3 {
            margin-top: 0;
        }

        .numero {
            font-size: 32px;
            font-weight: bold;
        }

        .peca {
            background: white;

            padding: 20px;

            margin-bottom: 12px;

            border-radius: 8px;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .abrir {
            background: #222;
            color: white;

            padding: 10px 18px;

            border-radius: 6px;
        }

        .logout {
            background: transparent;
            color: white;

            border: 1px solid white;

            padding: 8px 15px;

            border-radius: 6px;

            cursor: pointer;
        }

    </style>
</head>

<body>

<header>

    <div>
        <strong>GÊMEOS INTERIORES</strong>
    </div>

    <div>

        {{ auth()->user()->name }}
        —
        {{ auth()->user()->cargo->nome }}

        <form
            method="POST"
            action="{{ route('logout') }}"
            style="display:inline; margin-left:20px;"
        >

            @csrf

            <button class="logout">
                Sair
            </button>

        </form>

    </div>

</header>


<main>

    <h1>Modelos</h1>

    <p>
        Consulte os modelos e informações técnicas da Gêmeos.
    </p>

    <a
    href="{{ route('pecas.create') }}"
    style="
        display:inline-block;
        background:#222;
        color:white;
        padding:12px 20px;
        border-radius:7px;
        text-decoration:none;
        margin-bottom:20px;
    ">
    + Novo Modelo
    </a>


    <form
        class="pesquisa"
        method="GET"
        action="{{ route('pecas.index') }}"
    >

        <input
            type="text"
            name="busca"
            placeholder="Pesquisar por nome ou código..."
        >

        <button type="submit">
            Pesquisar
        </button>

    </form>


    <div class="cards">

        <div class="card">

            <h3>Total de modelos</h3>

            <div class="numero">
                {{ $totalPecas }}
            </div>

        </div>


        @foreach ($tipos as $tipo)

            <a
                href="{{ route('pecas.index', ['tipo' => $tipo->id]) }}"
            >

                <div class="card">

                    <h3>
                        {{ $tipo->nome }}
                    </h3>

                    <div class="numero">
                        {{ $tipo->pecas_count }}
                    </div>

                </div>

            </a>

        @endforeach

    </div>


    <h2>Modelos recentes</h2>


    @forelse ($pecasRecentes as $peca)

        <div class="peca">

            <div>

                <strong>
                    {{ $peca->codigo }}
                </strong>

                <br>

                {{ $peca->nome }}

                <br>

                <small>
                    {{ $peca->tipo->nome }}
                </small>

            </div>


            <a
                class="abrir"
                href="{{ route('pecas.show', $peca) }}"
            >
                Abrir
            </a>

        </div>

    @empty

        <p>
            Nenhum modelo cadastrado.
        </p>

    @endforelse
    

</main>

</body>
</html>