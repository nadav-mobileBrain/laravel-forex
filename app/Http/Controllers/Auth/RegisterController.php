<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\BasicSetting;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Plan;
use App\Models\Post;
use App\Models\Social;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\TraitsFolder\CommonTrait;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;
    use CommonTrait;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $data['page_title'] = "Register Now";
        $data['social'] = Social::all();
        $data['plan'] = Plan::whereStatus(1)->get();
        $data['menus'] = Menu::all();
        $data['category'] = Category::all();
        $data['footer_category'] = Category::take(7)->get();
        $data['footer_blog'] = Post::orderBy('views', 'desc')->take(7)->get();
        $data['country'] = json_decode(file_get_contents(storage_path('json/country.json')), true);
        $data['referral'] = null;

        return view('auth.register', $data);
    }

    /**
     * @param $username
     */
    public function refererRegister($username)
    {
        $data['page_title'] = "Register Now";
        $data['social'] = Social::all();
        $data['plan'] = Plan::whereStatus(1)->get();
        $data['menus'] = Menu::all();
        $data['category'] = Category::all();
        $data['footer_category'] = Category::take(7)->get();
        $data['footer_blog'] = Post::orderBy('views', 'desc')->take(7)->get();
        $data['country'] = json_decode(file_get_contents(storage_path('json/country.json')), true);
        $data['referral'] = $username;

        return view('auth.register', $data);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users',
            'username'     => 'required|string|alpha_dash|max:255|min:5|unique:users|regex:/^\S*$/u',
            'phone'        => 'required|numeric|unique:users',
            'password'     => 'required|string|min:6|confirmed',
            'plan_id'      => 'required',
            'country_code' => 'required'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $basic = BasicSetting::first();
        $plan = Plan::findOrFail($data['plan_id']);
        if ($plan->price_type == 0) {
            $planStatus = 1;
            $freePlanStatus = 1;
        } else {
            $planStatus = 0;
            $freePlanStatus = 0;
        }
        $code = strtoupper(Str::random(6));

        if ($plan->plan_type == 0) {
            $expireTime = Carbon::parse()->addDays($plan->duration);
        } else {
            $expireTime = 1;
        }

        if ($basic->email_verify == 1) {
            $emailVerify = 0;
        } else {
            $emailVerify = 1;
        }
        $pCode = rand(11111, 99999);
        if ($basic->phone_verify == 1) {
            $phoneVerify = 0;
        } else {
            $phoneVerify = 1;
        }
        $telegramToken = strtoupper(Str::random(32));
        $parentId = 0;

        if (User::whereUsername($data['referral'])->exists()) {
            $parentId = User::whereUsername($data['referral'])->first()->id;
        }

        return User::create([
            'name'             => $data['name'],
            'email'            => $data['email'],
            'username'         => $data['username'],
            'phone'            => $data['phone'],
            'parent_id'        => $parentId,
            'country_code'     => $data['country_code'],
            'plan_id'          => $data['plan_id'],
            'plan_status'      => $planStatus,
            'telegram_token'   => $telegramToken,
            'email_code'       => $code,
            'email_status'     => $emailVerify,
            'phone_code'       => $pCode,
            'phone_status'     => $phoneVerify,
            'expire_time'      => $expireTime,
            'free_plan_status' => $freePlanStatus,
            'password'         => bcrypt($data['password'])
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    public function registered(Request $request, $user)
    {
        $basic = BasicSetting::first();
        if ($basic->email_verify == 1) {
            session()->flash('message', 'We Have Sent a Verification Code to the Email : ' . $user->email . ' Please Enter that Code below to Verify your Account');
            $user->email_expire = Carbon::parse()->addMinutes(3);
            $user->save();
            $this->verificationSend($user->id);
        }
        if ($basic->phone_verify == 1) {

            $user->phone_expire = Carbon::parse()->addMinutes(3);
            $user->save();

            $this->phoneVerification($user->id);

        }

        return redirect()->route('user-dashboard');
    }

}
