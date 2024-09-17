<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Post;
use App\Models\Social;
use App\Models\User;
use App\TraitsFolder\CommonTrait;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    use CommonTrait;
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
     */

    use SendsPasswordResetEmails;

    public function showLinkRequestForm()
    {
        $data['page_title'] = "Reset Password";
        $data['social'] = Social::all();
        $data['menus'] = Menu::all();
        $data['category'] = Category::all();
        $data['footer_category'] = Category::take(7)->get();
        $data['footer_blog'] = Post::orderBy('views', 'desc')->take(7)->get();

        return view('auth.passwords.email', $data);
    }

    /**
     * @param Request $request
     */
    protected function validateEmail(Request $request)
    {
        $this->validate($request, [
            'email'                => 'required|email|exists:users,email',
            'g-recaptcha-response' => 'captcha'
        ]);
    }

    /**
     * @param Request $request
     */
    public function sendResetLinkEmail(Request $request)
    {

        $this->validateEmail($request);

        $us = User::whereEmail($request->email)->count();
        if ($us == 0) {
            session()->flash('message', 'We can\'t find a user with that e-mail address.');
            session()->flash('type', 'danger');

            return redirect()->back();
        } else {
            $user1 = User::whereEmail($request->email)->first();
            $route = 'password.reset';
            $this->userPasswordReset($user1->email, $user1->name, $route);
            session()->flash('message', 'Password Reset Link Send Your E-mail');
            session()->flash('type', 'success');

            return redirect()->back();
        }

    }
}
