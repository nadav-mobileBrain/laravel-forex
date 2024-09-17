<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Post;
use App\Models\Type;
use App\Models\User;
use App\Models\Asset;
use App\Models\Frame;
use App\Models\Staff;
use App\Models\Signal;
use App\Models\Status;
use App\Models\Symbol;
use App\Models\Section;
use App\Models\Category;
use App\Models\Subscribe;
use App\Models\PaymentLog;
use App\Models\SmsGateway;
use App\Models\UserSignal;
use App\Models\EmailDriver;
use App\Models\WithdrawLog;
use Illuminate\Support\Str;
use App\Models\BasicSetting;
use App\Models\SignalRating;
use App\Models\StaffRequest;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\SignalComment;
use App\Models\TransactionLog;
use App\Models\WhatsappDriver;
use App\Models\SubscribeMessage;
use App\TraitsFolder\CommonTrait;
use App\Models\SubscribeMessageCron;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Chart\UserPlanChart;
use App\Http\Controllers\Chart\Last30DaysChart;
use App\Http\Controllers\Chart\SymbolPipsChart;

class DashboardController extends Controller
{
    use CommonTrait;
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * @return mixed
     */
    public function getDashboard()
    {
        $data['page_title'] = "Dashboard";
        $data['signal'] = Signal::count();
        $data['blog'] = Post::count();
        $data['category'] = Category::count();
        $data['user'] = User::count();
        $data['win_pips'] = Signal::whereWin(1)->sum('pips');
        $data['loss_pips'] = Signal::whereWin(2)->sum('pips');
        $data['active_payment'] = PaymentMethod::whereStatus(true)->count();
        $data['active_email'] = EmailDriver::whereStatus(true)->count();
        $data['active_whatsapp'] = WhatsappDriver::whereStatus(true)->count();
        $data['active_sms'] = SmsGateway::whereStatus(true)->count();
        $data['total_assets'] = Asset::count();
        $data['total_symbol'] = Symbol::count();
        $data['total_type'] = Type::count();
        $data['total_frame'] = Frame::count();
        $data['total_status'] = Status::count();
        $data['total_subscriber'] = Subscribe::count();
        $data['total_staff'] = Staff::count();
        $data['active_whatsapp_user'] = User::whereNotNull('whatsapp_id')->count();
        $data['active_telegram_user'] = User::whereNotNull('telegram_id')->count();

        $data['pipsChart'] = (new SymbolPipsChart())->generate();
        $data['line'] = (new Last30DaysChart())->generate();
        $data['userPlan'] = (new UserPlanChart())->generate();

        return view('dashboard.dashboard', $data);
    }

    /**
     * @param Request $request
     */
    public function manageUser(Request $request)
    {
        $data['page_title'] = "Manage User";
        $basic = BasicSetting::first();
        if ($request->ajax()) {
            $users = User::orderByDesc('id')->select([
                'id',
                'name',
                'email',
                'phone',
                'balance',
                'email_status',
                'phone_status',
                'telegram_id',
                'plan_id',
                'plan_status',
                'status',
                DB::raw('CONCAT(name, "<br>", email, "<br>", phone) as user_details'),
            ])->with('plan:id,name');

            return DataTables::of($users)
                ->addIndexColumn()
                ->filterColumn('user_details', function ($query, $keyword) {
                    $query->whereRaw('CONCAT(name, "<br>", email, "<br>", phone) like ?', ["%{$keyword}%"]);
                })
                ->setRowClass(function ($row) {
                    return $row->plan_status == 0 ? 'bg-warning' : '';
                })
                ->editColumn('telegram_id', function ($row) {
                    return $row->telegram_id ?? 'NULL';
                })
                ->editColumn('balance', function ($row) use ($basic) {
                    return $basic->symbol . $row->balance;
                })
                ->editColumn('email_status', function ($row) {
                    $text = $row->email_status ? "Verified" : "Unverified";
                    $btnText = $row->email_status ? "Make Unverified" : "Make Verified";
                    $icon = $row->email_status ? "check" : "times";
                    $class = $row->email_status ? 'primary' : 'warning';

                    return "<button type='button' class='btn btn-{$class} btn-mini bold uppercase email_button' data-toggle='modal' data-target='#EmailModal' data-id='{$row->id}' title='{$btnText}'><i class='fa fa-{$icon}'></i>{$text}</button>";
                })
                ->editColumn('phone_status', function ($row) {
                    $text = $row->phone_status ? "Verified" : "Unverified";
                    $btnText = $row->phone_status ? "Make Unverified" : "Make Verified";
                    $icon = $row->phone_status ? "check" : "times";
                    $class = $row->phone_status ? 'primary' : 'warning';

                    return "<button type='button' class='btn btn-{$class} btn-mini bold uppercase phone_button' data-toggle='modal' data-target='#PhoneModal' data-id='{$row->id}' title='{$btnText}'><i class='fa fa-{$icon}'></i>{$text}</button>";
                })
                ->editColumn('status', function ($row) {
                    $text = !$row->status ? "Active" : "Block";
                    $btnText = !$row->status ? "Make Block" : "Make Active";
                    $icon = !$row->status ? "check" : "times";
                    $class = !$row->status ? 'primary' : 'warning';

                    return "<button type='button' class='btn btn-{$class} btn-mini bold uppercase block_button' data-toggle='modal' data-target='#DelModal' data-id='{$row->id}' title='{$btnText}'><i class='fa fa-{$icon}'></i>{$text}</button>";
                })
                ->editColumn('plan_status', function ($row) {
                    $class = $row->plan_status ? 'success' : 'danger';
                    $text = $row->plan_status ? 'Completed' : 'Unpaid';
                    $icon = $row->plan_status ? 'check' : 'times';

                    return "<div class='badge badge-{$class}'><i class='fa fa-{$icon}'></i> {$text}</div>";
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('user-edit', $row->id) . '" class="btn btn-primary btn-mini bold uppercase" title="Edit"><i class="fa fa-edit"></i> Edit</a>';
                    $btn .= ' <button type="button" class="btn btn-danger btn-mini bold uppercase confirm_button" data-toggle="modal" data-target="#ConModal" data-id="' . $row->id . '" title="Delete"> <i class="fa fa-trash"></i> Delete </button>';

                    return $btn;
                })
                ->rawColumns(['user_details', 'email_status', 'phone_status', 'status', 'plan_status', 'action'])
                ->make(true);
        }

        return view('dashboard.manage-user', $data);
    }

    /**
     * @param Request $request
     */
    public function blockUser(Request $request)
    {
        $request->validate([
            'block_id' => 'required',
        ]);
        $user = User::findOrFail($request->block_id);
        if ($user->status == 1) {
            $user->status = 0;
            $user->save();
        } else {
            $user->status = 1;
            $user->save();
        }
        session()->flash('message', 'Successfully Done');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    public function blockEmail(Request $request)
    {

        $request->validate([
            'email_id' => 'required',
        ]);
        $user = User::findOrFail($request->email_id);
        if ($user->email_status == 1) {
            $user->email_status = 0;
            $user->save();
        } else {
            $user->email_status = 1;
            $user->save();
        }
        session()->flash('message', 'Successfully Done');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    public function blockPhone(Request $request)
    {
        $request->validate([
            'phone_id' => 'required',
        ]);
        $user = User::findOrFail($request->phone_id);
        if ($user->phone_status == 1) {
            $user->phone_status = 0;
            $user->save();
        } else {
            $user->phone_status = 1;
            $user->save();
        }
        session()->flash('message', 'Successfully Done');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    public function googleRecaptcha()
    {
        $data['page_title'] = 'Google Recaptcha';

        return view('dashboard.google-recaptcha', $data);
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function updateRecaptcha(Request $request, $id)
    {
        $request->validate([
            'captcha_secret' => 'required',
            'captcha_site'   => 'required',
        ]);
        $basic = BasicSetting::first();
        $basic->captcha_status = $request->captcha_status == 'on' ? 1 : 0;
        $basic->captcha_secret = $request->captcha_secret;
        $basic->captcha_site = $request->captcha_site;
        $basic->save();
        session()->flash('message', 'Captcha Updated Successfully.');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    public function createUser()
    {
        $data['page_title'] = 'Add new User';
        $data['plan'] = Plan::whereStatus(1)->get();
        $data['country'] = json_decode(file_get_contents(storage_path('json/country.json')), true);

        return view('dashboard.user-create', $data);
    }

    /**
     * @param Request $request
     */
    public function submitUser(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'username'     => 'required|unique:users,username',
            'email'        => 'required|email|unique:users,email',
            'phone'        => 'required|unique:users,phone',
            'country_code' => 'required',
            'plan_id'      => 'required',
            'password'     => 'required|min:5|confirmed',
        ]);
        $plan = Plan::findOrFail($request->plan_id);
        $in = $request->except('_method', '_token', 'password_confirmation');
        $in['password'] = Hash::make($request->password);
        $in['email_code'] = strtoupper(Str::random('6'));
        $in['telegram_token'] = strtoupper(Str::random('32'));
        if ($plan->plan_type == 1) {
            $in['expire_time'] = 1;
        } else {
            $in['expire_time'] = Carbon::parse()->addDays($plan->duration);
        }
        $in['email_status'] = 1;
        $in['phone_code'] = rand(11111, 99999);
        $in['phone_status'] = 1;
        $in['plan_status'] = $request->plan_status == 'on' ? '1' : '0';

        User::create($in);
        session()->flash('message', 'User Added Successfully');
        session()->flash('type', 'success');

        return redirect()->back();

    }

    /**
     * @param $id
     */
    public function editUser($id)
    {
        $data['page_title'] = 'Edit User';
        $data['user'] = User::findOrFail($id);
        $data['plan'] = Plan::whereStatus(1)->get();
        $data['country'] = json_decode(file_get_contents(storage_path('json/country.json')), true);

        return view('dashboard.user-edit', $data);
    }

    /**
     * @param Request $request
     */
    public function updateUser(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $request->validate([
            'name'         => 'required',
            'country_code' => 'required',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'username'     => 'required|unique:users,username,' . $user->id,
            'phone'        => 'required|unique:users,phone,' . $user->id,
            'plan_id'      => 'required',
            'expire_time'  => 'required',
        ]);
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->country_code = $request->country_code;
        $user->phone = $request->phone;
        $user->plan_status = $request->plan_status == 'on' ? '1' : '0';
        $user->plan_id = $request->plan_id;

        if ($request->expire_time != 1) {
            $user->expire_time = Carbon::parse($request->expire_time)->format('Y-m-d h:i:s');
        }
        if ($user->up_status == 1) {
            $user->up_status = 0;
            $user->up_plan_id = null;
        }
        if ($request->telegram_status == 'on') {
            $user->telegram_id = null;
            $user->telegram_token = strtoupper(Str::random(32));
        }
        if ($request->whatsapp_status == 'on') {
            $user->whatsapp_id = null;
            $user->whatsapp_token = strtoupper(Str::random(12));
        }
        $user->save();
        session()->flash('message', 'User Updated Successfully.');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    public function deleteUser(Request $request)
    {
        $user = User::findOrFail($request->id);
        UserSignal::whereUser_id($user->id)->delete();
        TransactionLog::whereUser_id($user->id)->delete();
        PaymentLog::whereUser_id($user->id)->delete();
        WithdrawLog::whereUser_id($user->id)->delete();
        $user->delete();
        session()->flash('message', 'User Deleted Successfully');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    public function manageSubscriber()
    {
        $data['page_title'] = 'All Subscriber';
        $data['subscriber'] = Subscribe::latest()->paginate(15);

        return view('dashboard.subscriber', $data);
    }

    /**
     * @param Request $request
     */
    public function submitSubscriberMessage(Request $request)
    {
        $request->validate([
            'title'          => 'required',
            'message'        => 'required',
            'subscriber_ids' => 'array|required',
        ]);

        $in = $request->except('_method', '_token', 'subscriber_ids');
        $subscribe = SubscribeMessage::create($in);

        foreach ($request->subscriber_ids as $sub_id) {
            $ss['message_id'] = $subscribe->id;
            $ss['subscriber_id'] = $sub_id;
            SubscribeMessageCron::create($ss);
        }

        session()->flash('message', 'Subscriber Message Submitted');
        session()->flash('type', 'success');

        return redirect()->back();

    }

    public function subscriberMessageList()
    {
        $data['page_title'] = 'Subscriber Message List';
        $data['message'] = SubscribeMessage::orderBy('id', 'desc')->paginate(10);

        return view('dashboard.subscriber-message-list', $data);
    }

    /**
     * @param Request $request
     */
    public function subscriberMessageDelete(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        SubscribeMessageCron::whereMessage_id($request->id)->delete();
        SubscribeMessage::destroy($request->id);
        session()->flash('message', 'Subscriber Message Deleted');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    public function deleteSubscriber(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        SubscribeMessageCron::whereSubscriber_id($request->id)->delete();
        Subscribe::destroy($request->id);
        session()->flash('message', 'Subscriber Deleted Successfully');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    public function getSubscriberMessage()
    {
        $data['page_title'] = 'Subscriber Message';
        $data['subscriber'] = Subscribe::all();

        return view('dashboard.subscriber-message', $data);
    }

    public function getCurrencyWidget()
    {
        $data['page_title'] = 'Currency Widget';

        return view('dashboard.currency-widget', $data);
    }

    /**
     * @param Request $request
     */
    public function submitCurrencyWidget(Request $request)
    {
        $section = Section::first();
        $section->currency_live = $request->currency_live;
        $section->currency_cal = $request->currency_cal;
        $section->save();
        session()->flash('message', 'Currency Widget Updated Successfully');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    public function staffRequest()
    {
        $data['page_title'] = "Staff Request";
        $data['user'] = StaffRequest::latest()->paginate(10);

        return view('dashboard.staff-request', $data);
    }

    /**
     * @param Request $request
     */
    public function staffrequestApprove(Request $request)
    {
        $request->validate([
            'confirm_id' => 'required',
        ]);
        $stff = StaffRequest::whereUser_id($request->confirm_id)->first();
        $stff->status = 1;
        $stff->save();

        $user = User::findOrFail($request->confirm_id);
        $user->signal_status = 1;
        $user->save();

        session()->flash('message', 'Staff request Approve.');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    public function staffrequestReject(Request $request)
    {
        $request->validate([
            'reject_id' => 'required',
        ]);
        $stff = StaffRequest::whereUser_id($request->reject_id)->first();
        $stff->status = 2;
        $stff->save();

        $user = User::findOrFail($request->reject_id);
        $user->signal_status = 3;
        $user->save();

        session()->flash('message', 'Staff request Reject.');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    public function getTransactionLog()
    {
        $data['page_title'] = "Transaction Log";
        $data['log'] = TransactionLog::orderBy('id', 'desc')->paginate(20);

        return view('dashboard.transaction-log', $data);
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
        $in['user_id'] = 0;
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
        $in['user_id'] = 0;
        SignalRating::create($in);
        \session()->flash('message', 'Rating Submitted Successfully.');
        \session()->flash('type', 'success');

        return redirect()->back();
    }

    public function createStaff()
    {
        $data['page_title'] = "Create Staff";
        $data['country'] = json_decode(file_get_contents(storage_path('json/country.json')), true);

        return view('dashboard.staff-create', $data);
    }

    /**
     * @param Request $request
     */
    public function submitStaff(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'email'        => 'required|email|unique:staff',
            'country_code' => 'required',
            'phone'        => 'required',
            'password'     => 'required|min:5|confirmed',
        ]);
        $in = $request->except('_method', '_token', 'password_confirmation');
        $in['password'] = Hash::make($request->password);
        $in['status'] = $request->status == 'on' ? 1 : 0;
        $in['permissions'] = $request->input('permissions');
        Staff::create($in);
        session()->flash('message', 'Staff Created Successfully.');
        session()->flash('type', 'success');
        return redirect()->back();
    }

    public function manageStaff()
    {
        $data['page_title'] = "All Staff";
        $data['staff'] = Staff::orderBy('id', 'desc')->paginate(15);
        return view('dashboard.staff-all', $data);
    }

    /**
     * @param Request $request
     */
    public function passwordUpdateStaff(Request $request)
    {
        $request->validate([
            'id'       => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $staff = Staff::findOrFail($request->id);
        $staff->password = Hash::make($request->password);
        $staff->save();

        session()->flash('message', 'Staff Password Updated.');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    /**
     * @param $id
     */
    public function editStaff($id)
    {
        $data['page_title'] = "Edit Staff";
        $data['staff'] = Staff::findOrFail($id);
        $data['country'] = json_decode(file_get_contents(storage_path('json/country.json')), true);
        $data['permissions'] = $data['staff']->permissions ?? [];
        return view('dashboard.staff-edit', $data);
    }

    /**
     * @param Request $request
     */
    public function updateStaff(Request $request)
    {
        $staff = Staff::findOrFail($request->id);
        $request->validate([
            'name'         => 'required',
            'email'        => 'required|email|unique:staff,email,' . $staff->id,
            'country_code' => 'required',
            'phone'        => 'required',
        ]);

        $in = $request->except('_method', '_token', 'id');
        $in['status'] = $request->status == 'on' ? 1 : 0;
        $staff->update($in);
        session()->flash('message', 'Staff Updated Successfully.');
        session()->flash('type', 'success');
        return redirect()->back();
    }
}
