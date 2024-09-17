<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    protected $redirectTo = '/staff-dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest:staff')->except('logout');
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
        return view('staff.login');
    }

    /**
     * @param Request $request
     */
    public function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username()      => 'required|string',
            'password'             => 'required|string',
            'g-recaptcha-response' => 'captcha',
        ]);
    }

    public function guard()
    {
        return Auth::guard('staff');
    }

    /**
     * @param Request $request
     * @param $user
     */
    public function authenticated(Request $request, $user)
    {
        if (!$user->status) {
            Auth::guard('staff')->logout();
            return back()->with(['message' => 'Opps. Your account is blocked.']);
        }
    }

    public function username()
    {
        return 'email';
    }

    public function logout()
    {
        $this->guard('staff')->logout();
        session()->flash('message', 'Just Logged Out!');

        return redirect('/staff');
    }

}
