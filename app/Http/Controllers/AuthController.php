<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostLoginRequest;
use App\Http\Requests\PostRegistrationRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('auth.login');
    }


    /**
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function registration(): View|\Illuminate\Foundation\Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('auth.registration');
    }


    /**
     * @param PostLoginRequest $request
     * @return mixed
     */
    public function postLogin(PostLoginRequest $request): mixed
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
     * @return Application|View|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
     */
    public function dashboard(): Application|View|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
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
    public function create(array $data): mixed
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }


    /**
     * @return Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
     */
    public function logout(): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }
}
