<?php

namespace App\Http\Controllers;

use App\Models\Login;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function registerIndex(){
        return view('User.register');
    }

    public function postRegister(Request $request) {
        
        $username = $request['username'];
        $email = $request['email'];
        $password = bcrypt($request['password']);

        $user = DB::table('users')->where('email', $email)->first();
        if($user){
            return back()->withErrors([
                'email' => 'The user already exists !',
            ])->onlyInput('email');
        }else{
           
            $user = new User();
            $user->email = $email;
            $user->name = $username;
            $user->password = $password;

            $user->save();
            
            return redirect()->route('Login');    
        }
    }


    public function loginIndex(){
        return view('User.login');
    }

    public function postLogin(Request $request)
    {

        $credentials  = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect('/user/login');
    }
}
