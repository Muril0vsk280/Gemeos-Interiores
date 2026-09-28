<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Gêmeos Interiores</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;

            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            width: 100%;
            max-width: 420px;

            background: white;

            padding: 40px;

            border-radius: 12px;

            box-shadow: 0 10px 30px rgba(0,0,0,0.10);
        }

        h1 {
            margin-bottom: 8px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
        }

        .campo {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;

            border: 1px solid #ccc;
            border-radius: 6px;

            font-size: 16px;
        }

        button {
            width: 100%;

            padding: 13px;

            border: 0;
            border-radius: 6px;

            background: #222;
            color: white;

            font-size: 16px;
            font-weight: bold;

            cursor: pointer;
        }

        button:hover {
            background: #000;
        }

        .erro {
            background: #ffe5e5;
            color: #a40000;

            padding: 12px;
            border-radius: 6px;

            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="login-container">

        <h1>Gêmeos Interiores</h1>

        <p class="subtitle">
            Sistema interno de modelos
        </p>

        @if ($errors->any())
            <div class="erro">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.process') }}">

            @csrf

            <div class="campo">
                <label for="email">E-mail</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
            </div>

            <div class="campo">
                <label for="password">Senha</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                >
            </div>

            <button type="submit">
                Entrar
            </button>

        </form>

    </div>

</body>
</html>