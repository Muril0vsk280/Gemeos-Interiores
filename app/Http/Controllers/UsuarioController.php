<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Peca;
use App\Models\Historico;
use Illuminate\Support\Facades\Log;

class UsuarioController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTAR USUÁRIOS
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $usuarios = User::with('cargo')
            ->orderBy('name')
            ->get();

        return view(
            'usuarios.index',
            compact('usuarios')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TELA DE CADASTRO
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $cargos = Cargo::orderBy('nome')
            ->get();

        return view(
            'usuarios.create',
            compact('cargos')
        );
    }

    public function destroy(
    Request $request,
    User $usuario
) {
    /*
    |--------------------------------------------------------------------------
    | CONFIRMAÇÃO
    |--------------------------------------------------------------------------
    */

    $request->validate([
        'confirmacao_email' => [
            'required',
            'string',
        ],
    ]);


    if (
        trim($request->confirmacao_email)
        !==
        $usuario->email
    ) {
        return back()
            ->withErrors([
                'confirmacao_email' =>
                    'O e-mail digitado não corresponde ao usuário.',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | NÃO PODE EXCLUIR A PRÓPRIA CONTA
    |--------------------------------------------------------------------------
    */

    if (auth()->id() === $usuario->id) {

        return back()
            ->withErrors([
                'confirmacao_email' =>
                    'Você não pode excluir sua própria conta.',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | NÃO PODE DEIXAR O SISTEMA SEM ADMINISTRADOR ATIVO
    |--------------------------------------------------------------------------
    */

    $usuario->load('cargo');


    if (
        $usuario->cargo?->nome ===
        'Administrador'
    ) {

        $outroAdministradorAtivo =
            User::where(
                'id',
                '!=',
                $usuario->id
            )
                ->where('active', true)
                ->whereHas(
                    'cargo',
                    function ($query) {

                        $query->where(
                            'nome',
                            'Administrador'
                        );
                    }
                )
                ->exists();


        if (!$outroAdministradorAtivo) {

            return back()
                ->withErrors([
                    'confirmacao_email' =>
                        'Este usuário não pode ser excluído porque não existe outro Administrador ativo.',
                ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFICA SE EXISTE HISTÓRICO
    |--------------------------------------------------------------------------
    |
    | Não vamos apagar um usuário que já realizou ações importantes,
    | porque perderíamos a identificação de quem fez cada alteração.
    |
    */

    $temModelosCriados =
        Peca::where(
            'created_by',
            $usuario->id
        )->exists();


    $temHistorico =
        Historico::where(
            'user_id',
            $usuario->id
        )->exists();


    if (
        $temModelosCriados ||
        $temHistorico
    ) {

        return back()
            ->withErrors([
                'confirmacao_email' =>
                    'Este usuário já possui registros no sistema e não pode ser excluído permanentemente. Desative a conta em vez de excluí-la.',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | GUARDA INFORMAÇÕES PARA O LOG
    |--------------------------------------------------------------------------
    */

    $usuarioId =
        $usuario->id;

    $nome =
        $usuario->name;

    $email =
        $usuario->email;

    $cargo =
        $usuario->cargo?->nome;

    $administrador =
        auth()->user()->name;


    /*
    |--------------------------------------------------------------------------
    | EXCLUI USUÁRIO
    |--------------------------------------------------------------------------
    */

    $usuario->delete();


    /*
    |--------------------------------------------------------------------------
    | LOG DE SEGURANÇA
    |--------------------------------------------------------------------------
    */

    Log::warning(
        'Usuário excluído permanentemente.',
        [
            'usuario_id' =>
                $usuarioId,

            'usuario_nome' =>
                $nome,

            'usuario_email' =>
                $email,

            'cargo' =>
                $cargo,

            'excluido_por' =>
                $administrador,

            'administrador_id' =>
                auth()->id(),
        ]
    );


    return redirect()
        ->route('usuarios.index')
        ->with(
            'sucesso',
            "Usuário {$nome} excluído permanentemente."
        );
}


    /*
    |--------------------------------------------------------------------------
    | CADASTRAR USUÁRIO
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $dados = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'cargo_id' => [
                'required',
                'exists:cargos,id',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);


        User::create([
            'name' => $dados['name'],
            'email' => $dados['email'],
            'cargo_id' => $dados['cargo_id'],

            'password' => Hash::make(
                $dados['password']
            ),

            'active' => true,
        ]);


        return redirect()
            ->route('usuarios.index')
            ->with(
                'sucesso',
                'Usuário criado com sucesso.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | TELA DE EDIÇÃO
    |--------------------------------------------------------------------------
    */

    public function edit(User $usuario)
    {
        $cargos = Cargo::orderBy('nome')
            ->get();

        return view(
            'usuarios.edit',
            compact(
                'usuario',
                'cargos'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR USUÁRIO
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        User $usuario
    ) {
        $dados = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',

                Rule::unique(
                    'users',
                    'email'
                )->ignore($usuario->id),
            ],

            'cargo_id' => [
                'required',
                'exists:cargos,id',
            ],

            'active' => [
                'required',
                'boolean',
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        


        /*
        |--------------------------------------------------------------------------
        | EVITA O ADMIN BLOQUEAR A PRÓPRIA CONTA
        |--------------------------------------------------------------------------
        */

        if (
            auth()->id() === $usuario->id &&
            !$dados['active']
        ) {
            return back()
                ->withErrors([
                    'active' =>
                        'Você não pode desativar sua própria conta.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | EVITA O ADMIN REMOVER O PRÓPRIO CARGO
        |--------------------------------------------------------------------------
        */

        if (auth()->id() === $usuario->id) {

            $cargoAdministrador =
                Cargo::where(
                    'nome',
                    'Administrador'
                )->first();


            if (
                $cargoAdministrador &&
                (int) $dados['cargo_id'] !==
                (int) $cargoAdministrador->id
            ) {
                return back()
                    ->withErrors([
                        'cargo_id' =>
                            'Você não pode remover seu próprio cargo de Administrador.',
                    ])
                    ->withInput();
            }
        }


        $usuario->name =
            $dados['name'];

        $usuario->email =
            $dados['email'];

        $usuario->cargo_id =
            $dados['cargo_id'];

        $usuario->active =
            $dados['active'];


        /*
        |--------------------------------------------------------------------------
        | NOVA SENHA É OPCIONAL
        |--------------------------------------------------------------------------
        */

        if (!empty($dados['password'])) {

            $usuario->password =
                Hash::make(
                    $dados['password']
                );
        }


        $usuario->save();


        return redirect()
            ->route('usuarios.index')
            ->with(
                'sucesso',
                'Usuário atualizado com sucesso.'
            );
    }
}