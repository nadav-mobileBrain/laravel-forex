<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\User;
use App\Models\Signal;
use App\Models\PaymentLog;
use App\Models\SignalPlan;
use App\Models\WithdrawLog;
use Illuminate\Support\Str;
use App\Models\SignalRating;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\SignalComment;
use App\Models\TelegramDriver;
use App\Models\TransactionLog;
use App\Models\WhatsappDriver;
use App\Models\WithdrawMethod;
use App\Models\PaymentLogImage;
use App\TraitsFolder\CommonTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Chart\UserLast30DaysChart;
use App\Http\Controllers\Chart\UserSymbolPipsChart;
use App\Http\Controllers\Payment\BlockIoController;
use App\Http\Controllers\Payment\BraintreeController;
use App\Http\Controllers\Payment\AuthorizeNetController;
use App\Http\Controllers\Payment\BTCBlockChainController;
use App\Http\Controllers\Settings\Whatsapp\WhatsappGlobalController;

class UserController extends Controller
{
    use CommonTrait;
    public function __construct()
    {
        $this->middleware('verifyUser');
        $this->middleware('auth');
    }

    public function getDashboard()
    {

        $data['page_title'] = "User Dashboard";
        $data['user'] = $user = Auth::user();
        $data['all_signal'] = SignalPlan::wherePlan_id($user->plan_id)->count();
        $data['line'] = (new UserLast30DaysChart())->generate();
        $data['pips'] = (new UserSymbolPipsChart())->generate();
        return view('user.dashboard', $data);
    }

    public function editProfile()
    {
        $data['page_title'] = "Edit User Profile";
        $data['admin'] = User::findOrFail(Auth::user()->id);
        $data['country'] = json_decode(file_get_contents(storage_path('json/country.json')), true);

        return view('user.edit-profile', $data);
    }

    /**
     * @param Request $request
     */
    public function updateProfile(Request $request)
    {
        $admin = User::findOrFail(Auth::user()->id);
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'phone' => 'required|min:5|unique:users,phone,' . $admin->id,
            'image' => 'mimes:png,jpg,jpeg',
        ]);
        $in = $request->except('_method', '_token');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('assets/images/' . $filename);
            Image::make($image)->resize(215, 215)->save($location);
            if ($admin->image != 'user-default.png') {
                File::delete(public_path("assets/images/$admin->image"));
            }
            $in['image'] = $filename;
        }
        $admin->fill($in)->save();
        session()->flash('message', 'Profile Updated Successfully.');
        session()->flash('title', 'Success');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    public function getChangePass()
    {
        $data['page_title'] = "Change Password";

        return view('user.change-password', $data);
    }

    /**
     * @param Request $request
     */
    public function postChangePass(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required',
            'password'         => 'required|min:5|confirmed',
        ]);
        try {
            $c_password = Auth::user()->password;
            $c_id = Auth::user()->id;

            $user = User::findOrFail($c_id);

            if (Hash::check($request->current_password, $c_password)) {

                $password = Hash::make($request->password);
                $user->password = $password;
                $user->save();
                session()->flash('message', 'Password Changes Successfully.');
                session()->flash('title', 'Success');
                Session::flash('type', 'success');

                return redirect()->back();
            } else {
                session()->flash('message', 'Current Password Not Match');
                Session::flash('type', 'warning');
                session()->flash('title', 'Opps');

                return redirect()->back();
            }

        } catch (\PDOException $e) {
            session()->flash('message', $e->getMessage());
            Session::flash('type', 'warning');
            session()->flash('title', 'Opps');

            return redirect()->back();
        }

    }

    public function AllSignal()
    {
        $data['page_title'] = 'All Signal';
        $user = Auth::user();
        $lock = false;
        if ($user->expire_time != 1 && Carbon::parse($user->expire_time)->isPast()) {
            $lock = true;
        } elseif ($user->plan_status == 0) {
            $lock = true;
        }
        $data['lock'] = $lock;
        $signalIds = SignalPlan::wherePlan_id($user->plan_id)->pluck('signal_id')->toArray();
        $data['signal'] = Signal::whereIn('id', $signalIds)
            ->with([
                'asset',
                'symbol',
                'type',
                'frame',
                'status',
            ])
            ->withCount('ratings')
            ->withSum('ratings', 'rating')
            ->orderByDesc('id')
            ->paginate(12);
        $data['user'] = $user;
        return view('user.signal-all', $data);
    }

    /**
     * @param $id
     */
    public function signalView($id)
    {

        $signalDetails = Signal::whereCustom($id)->exists();
        if ($signalDetails) {
            $user = Auth::user();
            $lock = false;
            if ($user->expire_time != 1 && Carbon::parse($user->expire_time)->isPast()) {
                $lock = true;
            } elseif ($user->plan_status == 0) {
                $lock = true;
            }
            if ($lock) {
                toastMessage('warning', 'Complete Plan Payment First.');
                return to_route('chose-payment-method');
            }

            $signalDetails = Signal::whereCustom($id)->first();
            $data['page_title'] = $signalDetails->title;
            $data['signal'] = $signalDetails;
            $data['total_comment'] = SignalComment::whereSignal_id($signalDetails->id)->count();
            $data['comments'] = SignalComment::whereSignal_id($signalDetails->id)->get();
            $data['total_rating'] = SignalRating::whereSignal_id($signalDetails->id)->count();
            $data['sum_rating'] = SignalRating::whereSignal_id($signalDetails->id)->sum('rating');
            if ($data['total_rating'] == 0) {
                $data['final_rating'] = 0;
            } else {
                $data['final_rating'] = round($data['sum_rating'] / $data['total_rating']);
            }
            $data['rating'] = SignalRating::whereSignal_id($signalDetails->id)->get();
            $data['user_rating'] = SignalRating::whereSignal_id($signalDetails->id)->whereUser_id(Auth::user()->id)->first();

            return view('user.signal-view', $data);
        } else {
            session()->flash('message', 'Something is Wrong.');
            session()->flash('type', 'warning');

            return redirect()->back();
        }
    }

    public function chosePayment()
    {
        $data['page_title'] = 'Chose Payment Method';
        $data['payment'] = PaymentMethod::whereStatus(1)->get();

        return view('user.payment-chose', $data);
    }

    /**
     * @param Request $request
     */
    public function submitPaymentMethod(Request $request)
    {
        $id = $request->id;
        if (Auth::user()->up_status) {
            $plan = Plan::findOrFail(Auth::user()->up_plan_id);
        } else {
            $plan = Plan::findOrFail(Auth::user()->plan_id);
        }

        $payment = PaymentMethod::findOrFail($id);

        $paymentLog['user_id'] = Auth::id();
        $paymentLog['plan_id'] = $plan->id;
        $paymentLog['payment_id'] = $id;
        $paymentLog['order_number'] = Str::random('16');
        $paymentLog['amount'] = $plan->price;
        $paymentLog['usd'] = round($plan->price * $payment->rate, 2);

        $log = PaymentLog::whereUserId(Auth::id())->wherePaymentId($id)->whereStatus(0)->first();
        if ($log) {
            $log->update($paymentLog);
        } else {
            $log = PaymentLog::create($paymentLog);
        }

        if ($id == 3) {
            $btc = (new BTCBlockChainController())->process($log);
            if (!$btc['success']) {
                session()->flash('message', $btc['message']);
                session()->flash('type', 'warning');
                return to_route('chose-payment-method');
            } else {
                $log->btc_acc = $btc['address'];
                $log->btc_amount = $btc['amount'];
                $log->status = 2; // hold
                $log->save();
                $data['qrCode'] = $btc['qrCode'];
            }
        } elseif ($id == 1008) {
            $blockIO = (new BlockIoController())->process($log, $payment->val3);
            if ($blockIO['success']) {
                $log->btc_acc = $blockIO['btc_wallet'];
                $log->btc_amo = $blockIO['btc_amount'];
                $log->status = 2; // 2 means hold
                $log->save();
                $data['qrCode'] = $blockIO['qr_code'];
            } else {
                toastMessage('warning', $blockIO['message']);
                return to_route('chose-payment-method');
            }
        } elseif ($id == 1016) {
            $action = (new AuthorizeNetController())->process($log);
            if ($action['success']) {
                $data['authorize'] = $action['payment'];
            } else {
                toastMessage('warning', $action['message']);
                return to_route('chose-payment-method');
            }
        } elseif ($id == 1021) {
            $action = (new BraintreeController())->process();
            if ($action['success']) {
                $data['token'] = $action['token'];
            } else {
                toastMessage('warning', $action['message']);
                return to_route('chose-payment-method');
            }
        }

        $data['page_title'] = 'Payment Overview';
        $data['log'] = $log;

        return view('user.payment-overview', $data);
    }

    /**
     * @param $usd
     */
    public function getUsdToBtc($usd)
    {

    }

    /**
     * @param Request $request
     */
    public function manualPaymentSubmit(Request $request)
    {
        $request->validate([
            'payment_log_id' => 'required',
            'images'         => 'required',
            'images.*'       => 'image|mimes:jpg,jpeg,png,gif',
        ]);
        $log = PaymentLog::findOrFail($request->payment_log_id);
        $log->message = $request->message;

        //dd($request->file('images'));

        if ($files = $request->file('images')) {
            foreach ($files as $file) {
                $filename = Str::random(16) . '.' . $file->getClientOriginalExtension();
                $location = ('assets/images/paymentimage') . '/' . $filename;
                Image::make($file)->save(public_path($location));
                $pm['payment_log_id'] = $request->payment_log_id;
                $pm['name'] = $filename;
                PaymentLogImage::create($pm);
            }
        }
        $log->save();

        $this->manualPaymentEmail($log->user_id, $log->id);

        \session()->flash('message', 'Submit Successfully Completed.');
        \session()->flash('type', 'success');

        return redirect()->route('user-dashboard');

    }

    public function getUpgradePlan()
    {
        $data['page_title'] = 'Upgrade Plan';
        $data['plan'] = Plan::whereStatus(1)->get();

        return view('user.upgrade-plan', $data);
    }

    /**
     * @param Request $request
     */
    public function updatePlanSubmit(Request $request)
    {
        $request->validate([
            'delete_id' => 'required',
        ]);
        $plan = Plan::findOrFail($request->delete_id);
        $user = User::findOrFail(Auth::user()->id);

        if ($user->plan_id == $plan->id) {
            \session()->flash("message", 'Selected Same Plan id.');
            \session()->flash('type', 'warning');

            return redirect()->back();
        }

        $pay = 0;

        if ($user->plan_status == 0) {
            $user->plan_id = $plan->id;
            if ($plan->price_type == 0) {
                $user->plan_id = $plan->id;
                $user->plan_status = 1;
                $user->free_plan_status = 1;
                $user->expire_time = $plan->plan_type == 0 ? Carbon::parse()->addDays($plan->duration) : 1;
            } else {
                $user->plan_status = 0;
                $user->expire_time = $plan->plan_type == 0 ? Carbon::parse()->addDays($plan->duration) : 1;
                $pay = 1;
            }
        } else {
            $user->up_status = 1;
            $user->up_plan_id = $plan->id;
            if ($plan->price_type == 0) {
                $user->plan_id = $plan->id;
                $user->plan_status = 1;
                $user->free_plan_status = 1;
                $user->expire_time = $plan->plan_type == 0 ? Carbon::parse()->addDays($plan->duration) : 1;
            } else {
                $pay = 1;
            }
        }
        $user->save();

        \session()->flash('message', 'Plan Update Successfully.');
        \session()->flash('type', 'success');
        if ($pay == 1) {
            return redirect()->route('plan-upgrade-payment');
        } else {
            return redirect()->route('user-dashboard');
        }
    }

    public function planUpgradePayment()
    {
        $data['page_title'] = 'Plan Upgrade Payment';
        if (Auth::user()->up_status) {
            $data['plan'] = Plan::findOrFail(Auth::user()->up_plan_id);
        } else {
            $data['plan'] = Plan::findOrFail(Auth::user()->plan_id);
        }
        $data['payment'] = PaymentMethod::whereStatus(1)->get();

        return view('user.plan-update-payment', $data);
    }

    public function activeWhatsapp()
    {
        $data['page_title'] = 'Active Whatsapp';
        $data['user'] = Auth::user();
        return view('user.active-whatsapp', $data);
    }

    /**
     * @param Request $request
     */
    public function submitActiveWhatsapp(Request $request)
    {
        $request->validate([
            'whatsapp_number' => 'required|numeric',
        ]);
        $user = Auth::user();
        $token = Str::upper(Str::random(12));
        $user->whatsapp_token = $token;
        $user->save();
        $payload['message'] = "Your Activation Token: " . $token;
        $payload['number'] = $request->input('whatsapp_number');

        $driver = WhatsappDriver::whereStatus(true)->inRandomOrder()->first();
        //$driver = WhatsappDriver::find(5);

        $action = (new WhatsappGlobalController($driver))->send($payload);
        if ($action['success']) {
            session(['wa_number' => $payload['number']]);
            toastMessage('success', "If Whatsapp Number is correct then you receive the token.");
        } else {
            session(['wa_number' => '']);
            toastMessage('warning', "Problem with Number or API.");
        }
        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    public function submitTokenWhatsapp(Request $request)
    {
        $request->validate([
            'whatsapp_token'  => 'required',
            'whatsapp_number' => 'required',
        ]);

        $user = Auth::user();
        if ($user->whatsapp_token == $request->input('whatsapp_token')) {
            $user->whatsapp_id = $request->input('whatsapp_number');
            $user->save();
            toastMessage('success', 'Whatsapp activated successfully.');
        } else {
            toastMessage('warning', 'Whatsapp Token is Invalid.');
        }
        return to_back();
    }

    public function activeTelegram()
    {
        $data['page_title'] = "Active Telegram";
        $data['user'] = Auth::user();
        $data['driver'] = TelegramDriver::first();
        return view('user.active-telegram', $data);
    }

    /**
     * @param Request $request
     */
    public function submitActiveTelegram(Request $request)
    {
        $telegram_text = Auth::user()->telegram_token;
        $user = Auth::user();
        $driver = TelegramDriver::first();

        $botToken = $driver->token;
        $web = 'https://api.telegram.org/bot' . $botToken;
        $update = file_get_contents($web . "/getUpdates");
        $updateArray = json_decode($update, true);
        $chatId = null;

        foreach ($updateArray['result'] as $arr) {
            if (array_key_exists('message', $arr)) {
                if (array_key_exists('text', $arr['message'])) {
                    if ($arr['message']['text'] == $telegram_text) {
                        $chatId = $arr['message']['chat']['id'];
                        break;
                    }
                }
            }
        }

        if ($chatId != null) {
            $user->telegram_id = $chatId;
            $user->save();
            $txt = "Hi, " . $user->name . " - Your Telegram Notification Activated Successfully.";
            file_get_contents($web . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode($txt));
            session()->flash('message', 'Telegram Signal Is Activated.');
            session()->flash('type', 'success');

            return redirect()->back();
        } else {
            session()->flash('message', 'Error In Telegram Token.');
            session()->flash('type', 'warning');

            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     */
    public function commentSubmit(Request $request)
    {
        $request->validate([
            'comment'   => 'required',
            'signal_id' => 'required',
        ]);
        $in = $request->except('_method', '_token');
        $in['user_id'] = Auth::user()->id;
        SignalComment::create($in);
        \session()->flash('message', 'Comment Submitted Successfully.');
        \session()->flash('type', 'success');

        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    public function ratingSubmit(Request $request)
    {
        $request->validate([
            'comment'   => 'required',
            'signal_id' => 'required',
            'rating'    => 'required',
        ]);

        $in = $request->except('_method', '_token');
        $in['user_id'] = Auth::user()->id;
        SignalRating::create($in);
        session()->flash('message', 'Rating Submitted Successfully.');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    public function withdrawNow()
    {
        $data['page_title'] = 'Withdraw method';
        $data['method'] = WithdrawMethod::whereStatus(1)->get();

        return view('user.withdraw-now', $data);
    }

    /**
     * @param $id
     */
    public function withdrawMethod($id)
    {
        $withdraw = WithdrawMethod::findOrFail($id);
        $data['page_title'] = 'Withdraw Via - ' . $withdraw->name;
        $data['method'] = $withdraw;
        $data['user'] = User::findOrFail(Auth::user()->id);

        return view('user.withdraw-preview', $data);
    }

    /**
     * @param $av
     * @param $amount
     * @param $min
     * @param $max
     * @return mixed
     */
    public function checkWithdraw($av, $amount, $min, $max)
    {
        if ($amount > $av) {
            $rr = [
                'errorStatus'  => 'yes',
                'errorDetails' => 'Amount Large Then Available Amount',
            ];

            return $result = json_encode($rr);
        } elseif ($amount < $min) {
            $rr = [
                'errorStatus'  => 'yes',
                'errorDetails' => 'Amount Small Then Minimum Amount',
            ];

            return $result = json_encode($rr);
        } elseif ($amount > $max) {
            $rr = [
                'errorStatus'  => 'yes',
                'errorDetails' => 'Amount Large Then Maximum Amount',
            ];

            return $result = json_encode($rr);
        } else {
            $rr = [
                'errorStatus'  => 'no',
                'errorDetails' => 'You Can withdraw This Amount.',
            ];

            return $result = json_encode($rr);
        }
    }

    /**
     * @param Request $request
     */
    public function withdrawConfirm(Request $request)
    {

        $request->validate([
            'method_id' => 'required',
            'amount'    => 'required|numeric',
            'details'   => 'required',
        ]);

        $user = User::findOrFail(Auth::user()->id);

        $method = WithdrawMethod::findOrFail($request->method_id);

        $available = $user->balance - $method->charge;
        $amount = $request->amount;

        if ($amount > $available) {
            session()->flash('message', 'Amount Large Then Available Amount');
            session()->flash('type', 'warning');

            return redirect()->route('user-withdraw-method', $method->id);
        } elseif ($amount < $method->withdraw_min) {
            session()->flash('message', 'Amount Small Then Minimum Amount');
            session()->flash('type', 'warning');

            return redirect()->route('user-withdraw-method', $method->id);
        } elseif ($amount > $method->withdraw_max) {
            session()->flash('message', 'Amount Small Then Maximum Amount');
            session()->flash('type', 'warning');

            return redirect()->route('user-withdraw-method', $method->id);
        } else {

            $withLog['custom'] = strtoupper(Str::random(12));

            $tr['custom'] = $withLog['custom'];
            $tr['user_id'] = $user->id;
            $tr['type'] = 4;
            $tr['balance'] = $method->charge;
            $tr['post_balance'] = $user->balance - ($request->amount + $method->charge);
            $tr['details'] = 'Withdraw Charge For ' . $method->name;
            TransactionLog::create($tr);

            $tr['custom'] = $withLog['custom'];
            $tr['user_id'] = $user->id;
            $tr['type'] = 3;
            $tr['balance'] = $request->amount;
            $tr['post_balance'] = $user->balance - $request->amount;
            $tr['details'] = 'Withdraw Via ' . $method->name;
            TransactionLog::create($tr);

            $withLog['user_id'] = $user->id;
            $withLog['method_id'] = $method->id;
            $withLog['amount'] = $request->amount;
            $withLog['charge'] = $method->charge;
            $withLog['details'] = $request->details;
            $withLog['status'] = 0;
            WithdrawLog::create($withLog);

            $user->balance = round($user->balance - ($request->amount + $method->charge), 2);
            $user->save();

            \session()->flash('message', 'Withdraw Request Accept.');
            \session()->flash('type', 'success');

            return redirect()->route('user-withdraw-now');
        }
    }

    public function withdrawHistory()
    {
        $data['page_title'] = 'Withdraw History';
        $data['log'] = WithdrawLog::whereUser_id(Auth::user()->id)->latest()->paginate(10);

        return view('user.withdraw-history', $data);
    }

    public function transactionLog()
    {
        $data['page_title'] = "Transaction Log";
        $data['log'] = TransactionLog::whereUser_id(Auth::user()->id)->orderBy('id', 'desc')->paginate(20);

        return view('user.transaction-log', $data);
    }

    public function referralUser()
    {
        $data['page_title'] = 'Referral Users';
        $data['users'] = User::with('plan:id,name')->whereParentId(Auth::id())->orderByDesc('id')->paginate(10);
        $data['user'] = Auth::user();
        return view('user.referral-users', $data);
    }
}
