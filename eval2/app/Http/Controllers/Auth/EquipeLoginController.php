<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Equipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class EquipeLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login-equipe');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'login' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('equipe')->attempt(['login' => $request->login, 'password' => $request->password])) {
            return redirect("/equipe");
        }

        return back()->withErrors([
            'login' => 'Verifier',
            'password' => 'Verifier'
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::guard('equipe')->logout();
        return redirect('/equipe/login');
    }

    public function inscription()
    {
        $user = Equipe::create([
            'nom' => 'Equipe 1',
            'login' => 'equipe1@gmail.com',
            'pwd' => Hash::make('1234')
        ]);
        $user = Equipe::create([
            'nom' => 'Equipe 2',
            'login' => 'equipe2@gmail.com',
            'pwd' => Hash::make('1234')
        ]);
    }
}
