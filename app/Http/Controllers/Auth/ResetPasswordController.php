<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Post;
use App\Models\Social;
use App\Providers\RouteServiceProvider;
use Auth;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
     */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::USER_DASHBOARD;

    protected function rules()
    {
        return [
            'token'                => 'required',
            'email'                => 'required|email|exists:users,email',
            'password'             => 'required|confirmed|min:6',
            'g-recaptcha-response' => 'captcha'
        ];
    }

    /**
     * @param Request $request
     */
    public function showResetForm(Request $request)
    {

        if (Auth::check()) {
            return redirect()->route('user-dashboard');
        }

        $token = $request->route()->parameter('token');

        $data['page_title'] = "Reset Password";
        $data['social'] = Social::all();
        $data['menus'] = Menu::all();
        $data['category'] = Category::all();
        $data['footer_category'] = Category::take(7)->get();
        $data['footer_blog'] = Post::orderBy('views', 'desc')->take(7)->get();

        return view('auth.passwords.reset', $data)->with(
            ['token' => $token, 'email' => $request->email]
        );
    }
}
