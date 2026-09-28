<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Modelos - Gêmeos Interiores</title>

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

        .topo {
            display: flex;
            justify-content: space-between;
            align-items: center;

            margin-bottom: 25px;
        }

        .voltar {
            text-decoration: none;
            color: #333;
        }

        .filtros {
            background: white;

            padding: 20px;

            border-radius: 10px;

            margin-bottom: 30px;

            display: flex;
            gap: 10px;

            box-shadow:
                0 4px 15px rgba(0,0,0,0.06);
        }

        .filtros input,
        .filtros select {
            padding: 13px;

            border: 1px solid #ccc;
            border-radius: 6px;

            font-size: 15px;
        }

        .filtros input {
            flex: 1;
        }

        .filtros button {
            border: none;

            background: #222;
            color: white;

            padding: 13px 24px;

            border-radius: 6px;

            cursor: pointer;
        }

        .resultado {
            background: white;

            padding: 20px;

            border-radius: 10px;

            margin-bottom: 12px;

            display: flex;
            justify-content: space-between;
            align-items: center;

            box-shadow:
                0 3px 10px rgba(0,0,0,0.05);
        }

        .codigo {
            font-size: 14px;
            font-weight: bold;
            color: #666;
        }

        .nome {
            font-size: 20px;
            font-weight: bold;

            margin-top: 5px;
        }

        .tipo {
            margin-top: 6px;
            color: #777;
        }

        .abrir {
            background: #222;
            color: white;

            text-decoration: none;

            padding: 10px 18px;

            border-radius: 6px;
        }

        .vazio {
            background: white;

            padding: 40px;

            text-align: center;

            border-radius: 10px;
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

    <div>
        {{ auth()->user()->name }}
    </div>

</header>

<main>

    <div class="topo">

        <div>

            <a
                href="{{ route('dashboard') }}"
                class="voltar"
            >
                ← Voltar
            </a>

            <h1>Modelos</h1>

        </div>

    </div>


    <form
        method="GET"
        action="{{ route('pecas.index') }}"
        class="filtros"
    >

        <input
            type="text"
            name="busca"
            value="{{ request('busca') }}"
            placeholder="Nome ou código do modelo..."
        >


        <select name="tipo">

            <option value="">
                Todos os tipos
            </option>

            @foreach ($tipos as $tipo)

                <option
                    value="{{ $tipo->id }}"
                    @selected(request('tipo') == $tipo->id)
                >
                    {{ $tipo->nome }}
                </option>

            @endforeach

        </select>


        <button type="submit">
            Pesquisar
        </button>

    </form>


    @forelse ($pecas as $peca)

        <div class="resultado">

            <div>

                <div class="codigo">
                    {{ $peca->codigo }}
                </div>

                <div class="nome">
                    {{ $peca->nome }}
                </div>

                <div class="tipo">
                    {{ $peca->tipo->nome }}
                </div>

            </div>


            <a
                href="{{ route('pecas.show', $peca) }}"
                class="abrir"
            >
                Abrir modelo
            </a>

        </div>

    @empty

        <div class="vazio">

            <h3>
                Nenhum modelo encontrado
            </h3>

            <p>
                Tente pesquisar por outro nome ou código.
            </p>

        </div>

    @endforelse


    <div style="margin-top: 30px;">

        {{ $pecas->links() }}

    </div>

</main>

</body>

</html>