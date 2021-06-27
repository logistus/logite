<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Country;

class LoginController extends Controller
{
  public function login()
  {
    $page = "Login";
    return view('auth.login', compact('page'));
  }

  public function register()
  {
    $page = "Register";
    $countries = Country::orderBy('name', 'asc')->get();
    return view('auth.register', compact('page', 'countries'));
  }

  public function authenticate(Request $request)
  {
    $request->validate([
      'username' => 'required',
      'password' => 'required'
    ]);

    $credentials = $request->only('username', 'password');


    if (Auth::attempt($credentials, $request->input('remember'))) {
      $request->session()->regenerate();
      $request->user()->last_login = now();
      $request->user()->save();

      return redirect()->intended();
    }

    return back()->with('status', ['danger', 'The provided credentials do not match our records.'])->withInput();
  }

  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
  }

  public function username()
  {
    return 'username';
  }
}
