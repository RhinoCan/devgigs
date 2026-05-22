<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserController extends Controller
{
    // Show Register Form
    public function create()
    {
      return view('users.register');
    }

    // Create new user
    public function store(Request $request)
    {
      $formFields = $request->validate([
        'name' => ['required', 'min:3'],
        'email' => ['required', 'email', Rule::unique('users', 'email')],
        'password' => 'required|confirmed|min:8'
      ]);

      // Hash the password
      $formFields['password'] = bcrypt($formFields['password']);

      // Create user
      $user = User::create($formFields);

      // Log in
      auth()->login($user);

      return redirect('/')->with('message', 'User created and logged in');
    }

    // Log user out
    public function logout(Request $request)
    {
      auth()->logout();

      $request->session()->regenerateToken();

      return redirect('/')->with('message', 'You have been logged out.');
    }

    // Show login form
    public function login()
    {
      return view('users.login');
    }

    // Authenticate user
    public function authenticate(Request $request)
    {
      $formFields = $request->validate([
        'email' => ['required', 'email'],
        'password' => 'required'
      ]);

      if(auth()->attempt($formFields)) {
        $request->session()->regenerate();

        return redirect('/')->with('message', 'You are now logged in');
      }

      return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('email');
    }

}

