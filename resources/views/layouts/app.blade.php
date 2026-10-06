<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Gêmeos Interiores')
    </title>

    <link
        rel="stylesheet"
        href="{{ asset('css/app.css') }}"
    >
</head>

<body>

@php

    $usuario = auth()->user();

    $cargoUsuario = $usuario?->cargo?->nome;

    $podeEditar = in_array(
        $cargoUsuario,
        [
            'Administrador',
            'Encarregado'
        ],
        true
    );

    $administrador =
        $cargoUsuario === 'Administrador';

@endphp


{{-- ============================================================ --}}
{{-- CABEÇALHO --}}
{{-- ============================================================ --}}

<header class="topbar">

    <div class="topbar-conteudo">

        <a
            href="{{ route('dashboard') }}"
            class="marca"
        >

            <div class="marca-icone">
                GI
            </div>

            <div class="marca-texto">

                <strong>
                    Gêmeos Interiores
                </strong>

                <small>
                    Sistema de Modelos
                </small>

            </div>

        </a>


        <nav class="menu-principal">

            <a
                href="{{ route('dashboard') }}"
                class="menu-link"
            >
                Início
            </a>


            <a
                href="{{ route('pecas.index') }}"
                class="menu-link"
            >
                Modelos
            </a>


            @if ($administrador)

                <a
                    href="{{ route('usuarios.index') }}"
                    class="menu-link"
                >
                    Administração
                </a>

            @endif


            <form
                method="POST"
                action="{{ route('logout') }}"
            >

                @csrf

                <button
                    type="submit"
                    class="botao-sair"
                >
                    Sair
                </button>

            </form>

        </nav>

    </div>

</header>


{{-- ============================================================ --}}
{{-- CONTEÚDO --}}
{{-- ============================================================ --}}

<main class="container-principal">

    {{-- USUÁRIO --}}

    @auth

        <div class="barra-usuario">

            <div>

                <strong>
                    {{ $usuario->name }}
                </strong>

                <span>
                    {{ $cargoUsuario }}
                </span>

            </div>

        </div>

    @endauth


    {{-- SUCESSO --}}

    @if (session('sucesso'))

        <div class="alerta alerta-sucesso">

            {{ session('sucesso') }}

        </div>

    @endif


    {{-- ERROS --}}

    @if ($errors->any())

        <div class="alerta alerta-erro">

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


    @yield('content')

</main>


@stack('scripts')

</body>

</html>