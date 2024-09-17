<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected $redirectTo = '/admin-dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * @var int
     */
    public $maxAttempts = 3;

    /**
     * @var int
     */
    public $decayMinutes = 3;

    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * @param Request $request
     */
    public function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username()      => 'required|string',
            'password'             => 'required|string',
            'g-recaptcha-response' => 'captcha'
        ]);
    }

    public function guard()
    {
        return Auth::guard('admin');
    }

    public function username()
    {
        return 'email';
    }

    public function logout()
    {
        $this->guard('admin')->logout();
        session()->flash('message', 'Just Logged Out!');

        return redirect('/admin');
    }

}
