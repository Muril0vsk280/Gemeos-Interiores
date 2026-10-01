<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Modelos - Gêmeos Interiores
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

            gap: 20px;

            margin-bottom: 25px;
        }

        h1 {
            margin: 0;
        }

        .botao {
            display: inline-block;

            background: #222;
            color: white;

            padding: 12px 18px;

            border-radius: 7px;

            text-decoration: none;

            font-weight: bold;

            border: none;

            cursor: pointer;
        }

        .filtros {
            background: white;

            padding: 20px;

            border-radius: 10px;

            margin-bottom: 25px;

            box-shadow:
                0 4px 15px rgba(0,0,0,0.05);
        }

        .filtros form {
            display: grid;

            grid-template-columns:
                2fr 1fr auto auto;

            gap: 10px;
        }

        input,
        select {
            width: 100%;

            padding: 12px;

            border: 1px solid #ccc;

            border-radius: 7px;

            font-size: 15px;
        }

        .limpar {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            padding: 12px 18px;

            background: #eee;
            color: #222;

            border-radius: 7px;

            text-decoration: none;

            font-weight: bold;
        }

        .resultado-info {
            color: #777;

            margin-bottom: 15px;
        }

        .lista {
            display: grid;

            grid-template-columns:
                repeat(auto-fill, minmax(280px, 1fr));

            gap: 15px;
        }

        .modelo {
            background: white;

            padding: 20px;

            border-radius: 10px;

            box-shadow:
                0 4px 15px rgba(0,0,0,0.05);
        }

        .codigo {
            color: #777;

            font-size: 13px;

            font-weight: bold;

            margin-bottom: 7px;
        }

        .modelo h2 {
            margin:
                0 0 8px;

            font-size: 21px;
        }

        .tipo {
            color: #777;

            margin-bottom: 20px;
        }

        .abrir {
            display: inline-block;

            padding: 9px 14px;

            background: #222;
            color: white;

            border-radius: 6px;

            text-decoration: none;

            font-weight: bold;
        }

        .sem-resultados {
            background: white;

            padding: 30px;

            border-radius: 10px;

            text-align: center;

            color: #777;
        }

        .paginacao {
            margin-top: 30px;
        }

        @media (max-width: 750px) {

            header {
                padding: 18px 20px;
            }

            .topo {
                flex-direction: column;
                align-items: flex-start;
            }

            .filtros form {
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


    <span>

        {{ auth()->user()->name }}

        @if (auth()->user()->cargo)

            —
            {{ auth()->user()->cargo->nome }}

        @endif

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



    @php

        $cargoUsuario = auth()->user()->cargo?->nome;

        $podeEditar = in_array(
            $cargoUsuario,
            [
                'Administrador',
                'Encarregado'
            ],
            true
        );

    @endphp


    <div class="topo">

        <div>

            <h1>
                Modelos
            </h1>

            <p style="color:#777;">
                Consulte os modelos cadastrados.
            </p>

        </div>


        @if ($podeEditar)

            <a
                href="{{ route('pecas.create') }}"
                class="botao"
            >
                + Novo modelo
            </a>

        @endif

    </div>


    {{-- ============================================================ --}}
    {{-- PESQUISA --}}
    {{-- ============================================================ --}}

    <div class="filtros">

        <form
            method="GET"
            action="{{ route('pecas.index') }}"
        >

            <input
                type="text"
                name="busca"
                value="{{ $busca }}"
                placeholder="Pesquisar por código ou nome..."
                autofocus
            >


            <select name="tipo">

                <option value="">
                    Todos os tipos
                </option>


                @foreach ($tipos as $tipo)

                    <option
                        value="{{ $tipo->id }}"
                        @selected(
                            (string) $tipoId ===
                            (string) $tipo->id
                        )
                    >

                        {{ $tipo->nome }}

                    </option>

                @endforeach

            </select>


            <button
                type="submit"
                class="botao"
            >
                Pesquisar
            </button>


            @if ($busca !== '' || $tipoId)

                <a
                    href="{{ route('pecas.index') }}"
                    class="limpar"
                >
                    Limpar
                </a>

            @endif

        </form>

    </div>


    {{-- ============================================================ --}}
    {{-- QUANTIDADE DE RESULTADOS --}}
    {{-- ============================================================ --}}

    <div class="resultado-info">

        @if ($pecas->total() === 1)

            1 modelo encontrado.

        @else

            {{ $pecas->total() }} modelos encontrados.

        @endif

    </div>


    {{-- ============================================================ --}}
    {{-- MODELOS --}}
    {{-- ============================================================ --}}

    @if ($pecas->count())

        <div class="lista">

            @foreach ($pecas as $peca)

                <div class="modelo">

                    <div class="codigo">

                        {{ $peca->codigo }}

                    </div>


                    <h2>

                        {{ $peca->nome }}

                    </h2>


                    <div class="tipo">

                        {{ $peca->tipo->nome }}

                    </div>


                    <a
                        href="{{ route(
                            'pecas.show',
                            $peca
                        ) }}"
                        class="abrir"
                    >
                        Abrir modelo
                    </a>

                </div>

            @endforeach

        </div>


        <div class="paginacao">

            {{ $pecas->links() }}

        </div>

    @else

        <div class="sem-resultados">

            <strong>
                Nenhum modelo encontrado.
            </strong>

            <p>
                Tente pesquisar por outro código,
                nome ou tipo.
            </p>

        </div>

    @endif

</main>

</body>

</html>