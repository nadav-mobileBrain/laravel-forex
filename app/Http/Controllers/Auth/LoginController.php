<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\Post;
use App\Models\Social;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
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
    protected $redirectTo = RouteServiceProvider::USER_DASHBOARD;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
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
        $data['page_title'] = "Login";
        $data['social'] = Social::all();
        $data['menus'] = Menu::all();
        $data['category'] = Category::all();
        $data['footer_category'] = Category::take(7)->get();
        $data['footer_blog'] = Post::orderBy('views', 'desc')->take(7)->get();

        return view('auth.login', $data);
    }

    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * @param Request $request
     * @param $user
     */
    public function authenticated(Request $request, $user)
    {
        if ($user->status) {
            Auth::guard()->logout();
            return back()->with(['message' => 'Opps. Your account is blocked.']);
        }
        if ($user->expire_time != 1) {
            if (Carbon::parse($user->expire_time)->isPast()) {
                $user->plan_status = 0;
                $user->save();
            }
        }
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

    /**
     * @param Request $request
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        session()->flash('message', 'Logout Successfully Completed.');
        session()->flash('type', 'success');

        return redirect('/login');
    }
}
