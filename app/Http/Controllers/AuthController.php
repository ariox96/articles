<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostLoginRequest;
use App\Http\Requests\PostRegistrationRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(PostLoginRequest $request): mixed
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()
                ->intended(route('article.index'))
                ->withSuccess('You have Successfully logged');
        }

        return redirect('login')
            ->withError('Oppes! You have entered invalid credentials');
    }
    public function showRegistrationForm(): View
    {
        return view('auth.registration');
    }



    public function register(PostRegistrationRequest $request): mixed
    {
        $data = $request->all();
        $this->create($data);

        return redirect(route('article.index'))
            ->withSuccess('Great! You have Successfully loggedin');
    }

    public function create(array $data): Model
    {
        return User::query()
            ->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function logout(): RedirectResponse
    {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }
}
