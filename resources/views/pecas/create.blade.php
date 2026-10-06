@extends('layouts.app')


@section('title', 'Novo modelo - Gêmeos Interiores')


@section('content')


{{-- ============================================================ --}}
{{-- VOLTAR --}}
{{-- ============================================================ --}}

<div style="margin-bottom:20px;">

    <a
        href="{{ route('pecas.index') }}"
        class="botao botao-secundario"
    >
        ← Voltar para modelos
    </a>

</div>


{{-- ============================================================ --}}
{{-- CABEÇALHO --}}
{{-- ============================================================ --}}

<div class="titulo-pagina">

    <h1>
        Novo modelo
    </h1>

    <p>
        Cadastre primeiro as informações principais.
        Depois você poderá adicionar fotos, medidas,
        materiais e as etapas de fabricação.
    </p>

</div>


{{-- ============================================================ --}}
{{-- FORMULÁRIO --}}
{{-- ============================================================ --}}

<div class="card">

    <form
        method="POST"
        action="{{ route('pecas.store') }}"
    >

        @csrf


        {{-- ======================================================== --}}
        {{-- IDENTIFICAÇÃO --}}
        {{-- ======================================================== --}}

        <div class="formulario-secao">

            <div class="formulario-secao-cabecalho">

                <h2>
                    Identificação
                </h2>

                <p>
                    Informações usadas para localizar o modelo.
                </p>

            </div>


            <div class="form-grid">

                {{-- CÓDIGO --}}

                <div class="campo">

                    <label for="codigo">
                        Código
                        <span class="campo-obrigatorio">*</span>
                    </label>

                    <input
                        type="text"
                        id="codigo"
                        name="codigo"
                        value="{{ old('codigo') }}"
                        placeholder="Ex: CAD-002"
                        autocomplete="off"
                        required
                        autofocus
                    >

                    @error('codigo')

                        <div class="mensagem-campo-erro">
                            {{ $message }}
                        </div>

                    @enderror

                    <small class="campo-ajuda">
                        Use o código pelo qual a peça já é conhecida na fábrica.
                    </small>

                </div>


                {{-- NOME --}}

                <div class="campo">

                    <label for="nome">
                        Nome do modelo
                        <span class="campo-obrigatorio">*</span>
                    </label>

                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        value="{{ old('nome') }}"
                        placeholder="Ex: Cadeira Roma"
                        autocomplete="off"
                        required
                    >

                    @error('nome')

                        <div class="mensagem-campo-erro">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- TIPO --}}

                <div class="campo campo-largo">

                    <label for="tipo_peca_id">
                        Tipo do modelo
                        <span class="campo-obrigatorio">*</span>
                    </label>

                    <select
                        id="tipo_peca_id"
                        name="tipo_peca_id"
                        required
                    >

                        <option value="">
                            Selecione o tipo
                        </option>


                        @foreach ($tipos as $tipo)

                            <option
                                value="{{ $tipo->id }}"
                                @selected(
                                    (string) old('tipo_peca_id') ===
                                    (string) $tipo->id
                                )
                            >
                                {{ $tipo->nome }}
                            </option>

                        @endforeach

                    </select>


                    @error('tipo_peca_id')

                        <div class="mensagem-campo-erro">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>

        </div>


        {{-- ======================================================== --}}
        {{-- INFORMAÇÕES --}}
        {{-- ======================================================== --}}

        <div class="formulario-secao">

            <div class="formulario-secao-cabecalho">

                <h2>
                    Informações gerais
                </h2>

                <p>
                    Estes campos são opcionais e podem ser alterados depois.
                </p>

            </div>


            {{-- DESCRIÇÃO --}}

            <div class="campo">

                <label for="descricao">
                    Descrição
                </label>

                <textarea
                    id="descricao"
                    name="descricao"
                    rows="4"
                    placeholder="Descreva brevemente este modelo..."
                >{{ old('descricao') }}</textarea>


                @error('descricao')

                    <div class="mensagem-campo-erro">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            {{-- OBSERVAÇÕES --}}

            <div class="campo">

                <label for="observacoes">
                    Observações
                </label>

                <textarea
                    id="observacoes"
                    name="observacoes"
                    rows="4"
                    placeholder="Alguma informação importante sobre este modelo..."
                >{{ old('observacoes') }}</textarea>


                @error('observacoes')

                    <div class="mensagem-campo-erro">
                        {{ $message }}
                    </div>

                @enderror

            </div>

        </div>


        {{-- ======================================================== --}}
        {{-- AVISO DO PRÓXIMO PASSO --}}
        {{-- ======================================================== --}}

        <div class="aviso-proximo-passo">

            <div class="aviso-proximo-passo-icone">
                ✓
            </div>

            <div>

                <strong>
                    Depois de salvar
                </strong>

                <p>
                    Você poderá adicionar a foto principal,
                    medidas, materiais e informações de
                    Marcenaria, Preparação, Cola e Estofamento.
                </p>

            </div>

        </div>


        {{-- ======================================================== --}}
        {{-- AÇÕES --}}
        {{-- ======================================================== --}}

        <div class="acoes-formulario">

            <a
                href="{{ route('pecas.index') }}"
                class="botao botao-secundario"
            >
                Cancelar
            </a>


            <button
                type="submit"
                class="botao botao-grande"
            >
                Salvar modelo
            </button>

        </div>

    </form>

</div>


@endsection