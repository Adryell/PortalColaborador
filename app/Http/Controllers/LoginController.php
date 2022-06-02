<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $erro = '';

        if($request->get('erro') == 1) {
            $erro = 'Usuário ou senha não existe';
        }
        if ($request->get('erro') == 2) {
            $erro = 'Necessário realizar o login para acessar';
        }

        return view('pages.auth.login', ['erro' => $erro]);
    }

    public function auth(Request $request)
    {
        $rules = [
            'user' => 'email',
            'password' => 'required'
        ];
        $feedback = [
            'user.email' => 'O campo email é obrigatório!',
            'password.required' => 'O campo senha é obrigatório'
        ];

        $request->validate($rules, $feedback);
        $email = $request->get('email');
        $password = $request->get('password');

        $user = new User();
        $exist = $user->where('email', $email)->where('password', $password)->get()->first();

        if(isset($exist->email)) {
            session_start();
            $_SESSION['email'] = $exist->email;
            $_SESSION['password'] = $exist->password;

            return redirect()->route('portal.home');
        } else {
            return redirect()->route('portal.login', ['erro' => 1]);
        }
    }

    public function logout()
    {
        session_destroy();

        return redirect()->route('portal.login');
    }
}
