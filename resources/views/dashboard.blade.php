<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Dashboard - Gêmeos Interiores
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
            align-items: center;
            justify-content: space-between;

            gap: 20px;
        }

        header strong {
            font-size: 18px;
        }

        .usuario {
            display: flex;
            align-items: center;

            gap: 15px;
        }

        .cargo {
            color: #bbb;

            font-size: 13px;
        }

        .logout {
            border: 1px solid #555;

            background: transparent;
            color: white;

            padding: 8px 12px;

            border-radius: 6px;

            cursor: pointer;
        }

        main {
            max-width: 1200px;

            margin: auto;

            padding: 40px 20px;
        }

        .topo {
            display: flex;

            align-items: center;
            justify-content: space-between;

            gap: 20px;

            margin-bottom: 30px;
        }

        .topo h1 {
            margin: 0 0 6px;

            font-size: 32px;
        }

        .subtitulo {
            margin: 0;

            color: #777;
        }

        .botao {
            display: inline-block;

            border: none;

            background: #222;
            color: white;

            padding: 12px 18px;

            border-radius: 7px;

            text-decoration: none;

            font-weight: bold;

            cursor: pointer;
        }

        .botao-secundario {
            display: inline-block;

            background: #eee;
            color: #222;

            padding: 10px 15px;

            border-radius: 6px;

            text-decoration: none;

            font-weight: bold;
        }

        .pesquisa {
            background: white;

            padding: 25px;

            border-radius: 10px;

            margin-bottom: 25px;

            box-shadow:
                0 4px 15px rgba(0,0,0,0.05);
        }

        .pesquisa h2 {
            margin-top: 0;
        }

        .pesquisa form {
            display: grid;

            grid-template-columns: 1fr auto;

            gap: 10px;
        }

        .pesquisa input {
            width: 100%;

            padding: 14px;

            border: 1px solid #ccc;

            border-radius: 7px;

            font-size: 16px;
        }

        .resumo {
            display: grid;

            grid-template-columns:
                repeat(auto-fit, minmax(180px, 1fr));

            gap: 15px;

            margin-bottom: 30px;
        }

        .card {
            background: white;

            padding: 22px;

            border-radius: 10px;

            box-shadow:
                0 4px 15px rgba(0,0,0,0.05);
        }

        .card-label {
            color: #777;

            font-size: 14px;

            margin-bottom: 8px;
        }

        .card-numero {
            font-size: 30px;

            font-weight: bold;
        }

        .secao {
            background: white;

            padding: 25px;

            border-radius: 10px;

            margin-bottom: 25px;

            box-shadow:
                0 4px 15px rgba(0,0,0,0.05);
        }

        .secao-topo {
            display: flex;

            justify-content: space-between;
            align-items: center;

            gap: 15px;

            margin-bottom: 20px;
        }

        .secao h2 {
            margin: 0;
        }

        .modelos {
            display: grid;

            grid-template-columns:
                repeat(auto-fill, minmax(260px, 1fr));

            gap: 15px;
        }

        .modelo {
            border: 1px solid #e5e5e5;

            padding: 18px;

            border-radius: 8px;
        }

        .codigo {
            color: #777;

            font-size: 13px;

            font-weight: bold;

            margin-bottom: 6px;
        }

        .modelo h3 {
            margin:
                0 0 7px;

            font-size: 19px;
        }

        .tipo {
            color: #777;

            margin-bottom: 15px;
        }

        .data {
            color: #999;

            font-size: 12px;

            margin-bottom: 15px;
        }

        .sem-dados {
            color: #888;
        }

        @media (max-width: 750px) {

            header {
                padding: 18px 20px;

                flex-direction: column;
                align-items: flex-start;
            }

            .topo {
                flex-direction: column;
                align-items: flex-start;
            }

            .pesquisa form {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>


<body>

<header>

    <strong>
        GÊMEOS INTERIORES
    </strong>


    <div class="usuario">

        <div>

            <div>
                {{ auth()->user()->name }}
            </div>

            <div class="cargo">
                {{ auth()->user()->cargo?->nome }}
            </div>

        </div>


        <form
            method="POST"
            action="{{ route('logout') }}"
        >

            @csrf

            <button
                type="submit"
                class="logout"
            >
                Sair
            </button>

        </form>

    </div>

</header>


<main>

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


    {{-- ============================================================ --}}
    {{-- CABEÇALHO --}}
    {{-- ============================================================ --}}

    <div class="topo">

        <div>

            <h1>
                Modelos da fábrica
            </h1>

            <p class="subtitulo">
                Consulte fotos, medidas, materiais e etapas de produção.
            </p>

        </div>


        <div style="
    display:flex;
    gap:10px;
    flex-wrap:wrap;
">

    @if ($podeEditar)

        <a
            href="{{ route('pecas.create') }}"
            class="botao"
        >
            + Novo modelo
        </a>

    @endif


    @if ($cargoUsuario === 'Administrador')

        <a
            href="{{ route('usuarios.index') }}"
            class="botao"
        >
            Gerenciar usuários
        </a>

    @endif

</div>

    </div>


    {{-- ============================================================ --}}
    {{-- PESQUISA RÁPIDA --}}
    {{-- ============================================================ --}}

    <div class="pesquisa">

        <h2>
            Encontrar modelo
        </h2>


        <form
            method="GET"
            action="{{ route('pecas.index') }}"
        >

            <input
                type="text"
                name="busca"
                placeholder="Digite o código ou nome do modelo..."
                autocomplete="off"
            >


            <button
                type="submit"
                class="botao"
            >
                Pesquisar
            </button>

        </form>

    </div>


    {{-- ============================================================ --}}
    {{-- RESUMO --}}
    {{-- ============================================================ --}}

    <div class="resumo">

        <div class="card">

            <div class="card-label">
                Total de modelos
            </div>

            <div class="card-numero">
                {{ $totalPecas }}
            </div>

        </div>


        @foreach ($tipos as $tipo)

            <div class="card">

                <div class="card-label">
                    {{ $tipo->nome }}
                </div>

                <div class="card-numero">
                    {{ $tipo->pecas_count }}
                </div>

            </div>

        @endforeach

    </div>


    {{-- ============================================================ --}}
    {{-- MODELOS RECENTES --}}
    {{-- ============================================================ --}}

    <div class="secao">

        <div class="secao-topo">

            <h2>
                Modelos recentes
            </h2>


            <a
                href="{{ route('pecas.index') }}"
                class="botao-secundario"
            >
                Ver todos
            </a>

        </div>


        @if ($pecasRecentes->count())

            <div class="modelos">

                @foreach ($pecasRecentes as $peca)

                    <div class="modelo">

                        <div class="codigo">
                            {{ $peca->codigo }}
                        </div>


                        <h3>
                            {{ $peca->nome }}
                        </h3>


                        <div class="tipo">
                            {{ $peca->tipo->nome }}
                        </div>


                        <div class="data">

                            Cadastrado em

                            {{ $peca->created_at->format(
                                'd/m/Y'
                            ) }}

                        </div>


                        <a
                            href="{{ route(
                                'pecas.show',
                                $peca
                            ) }}"
                            class="botao-secundario"
                        >
                            Abrir modelo
                        </a>

                    </div>

                @endforeach

            </div>

        @else

            <p class="sem-dados">
                Nenhum modelo cadastrado ainda.
            </p>

        @endif

    </div>

</main>

</body>

</html>