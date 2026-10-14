@extends('layouts.app')


@section('title', 'Editar material - Gêmeos Interiores')


@section('content')


<div style="margin-bottom:20px;">

    <a
        href="{{ route('pecas.show', $peca) }}"
        class="botao botao-secundario"
    >
        ← Voltar para o modelo
    </a>

</div>


<div class="titulo-pagina">

    <h1>
        Editar material
    </h1>

    <p>
        Altere as informações utilizadas neste modelo.
    </p>

</div>


<div class="card">

    <div class="modelo-descricao">

        <strong>
            {{ $pecaMaterial->material->nome }}
        </strong>

        <p style="margin-bottom:0;">

            Modelo:
            {{ $peca->codigo }}
            —
            {{ $peca->nome }}

            @if ($pecaMaterial->etapa)

                <br>

                Etapa:
                {{ $pecaMaterial->etapa->nome }}

            @endif

        </p>

    </div>


    <form
        method="POST"
        action="{{ route(
            'pecas.materiais.update',
            [
                $peca,
                $pecaMaterial
            ]
        ) }}"
    >

        @csrf
        @method('PUT')


        <div class="form-grid">

            <div class="campo">

                <label for="quantidade">
                    Quantidade
                </label>

                <input
                    type="number"
                    step="0.01"
                    min="0"
                    name="quantidade"
                    id="quantidade"
                    value="{{ old(
                        'quantidade',
                        $pecaMaterial->quantidade
                    ) }}"
                >

            </div>


            <div class="campo">

                <label for="unidade">
                    Unidade
                </label>

                <input
                    type="text"
                    name="unidade"
                    id="unidade"
                    placeholder="Ex: un, cm, m, kg"
                    value="{{ old(
                        'unidade',
                        $pecaMaterial->unidade
                    ) }}"
                >

            </div>


            <div class="campo campo-largo">

                <label for="observacao">
                    Observação
                </label>

                <textarea
                    name="observacao"
                    id="observacao"
                    rows="4"
                    placeholder="Observações sobre o uso deste material..."
                >{{ old(
                    'observacao',
                    $pecaMaterial->observacao
                ) }}</textarea>

            </div>


            <div class="campo-largo">

                <button
                    type="submit"
                    class="botao"
                >
                    Salvar alterações
                </button>

            </div>

        </div>

    </form>

</div>


{{-- ============================================================ --}}
{{-- EXCLUIR MATERIAL --}}
{{-- ============================================================ --}}

<div class="zona-perigo">

    <h3>
        Excluir material desta etapa
    </h3>

    <p>
        Use esta opção somente se
        <strong>{{ $pecaMaterial->material->nome }}</strong>
        não fizer mais parte desta etapa do modelo.
    </p>

    <p>
        O material continuará disponível no sistema caso seja
        utilizado por outros modelos.
    </p>


    <form
        method="POST"
        action="{{ route(
            'pecas.materiais.destroy',
            [
                $peca,
                $pecaMaterial
            ]
        ) }}"
        onsubmit="
            return confirm(
                'Tem certeza que deseja remover este material desta peça?'
            );
        "
    >

        @csrf
        @method('DELETE')


        <button
            type="submit"
            class="botao botao-perigo"
        >
            Excluir material
        </button>

    </form>

</div>


@endsection