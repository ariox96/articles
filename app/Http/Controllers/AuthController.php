<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostLoginRequest;
use App\Http\Requests\PostRegistrationRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     */
    public function index(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('auth.login');
    }


    /**
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     */
    public function registration(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('auth.registration');
    }


    /**
     * @param PostLoginRequest $request
     * @return mixed
     */
    public function postLogin(PostLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard')
                ->withSuccess('You have Successfully loggedin');
        }

        return redirect("login")->withSuccess('Oppes! You have entered invalid credentials');
    }


    /**
     * @param PostRegistrationRequest $request
     * @return mixed
     */
    public function postRegistration(PostRegistrationRequest $request): mixed
    {
        $data = $request->all();
        $this->create($data);
        return redirect("dashboard")->withSuccess('Great! You have Successfully loggedin');
    }


    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function dashboard(): \Illuminate\Foundation\Application|View|Factory|Application
    {
        if (Auth::check()) {
            return view('dashboard');
        }
        return redirect("login")->withSuccess('Opps! You do not have access');
    }


    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }


    /**
     * @return \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
     */
    public function logout(): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }
}
