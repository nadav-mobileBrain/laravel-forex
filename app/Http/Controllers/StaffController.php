<?php

namespace App\Http\Controllers;

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
use App\Models\Category;
use App\Models\PaymentLog;
use App\Models\SignalPlan;
use App\Models\UserSignal;
use App\Models\WithdrawLog;
use Illuminate\Support\Str;
use App\Models\BasicSetting;
use App\Models\SignalRating;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\SignalComment;
use App\Models\TransactionLog;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Chart\Last30DaysChart;
use App\Http\Controllers\Chart\SymbolPipsChart;
use App\Http\Controllers\Payment\PaymentAction;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:staff');
    }

    public function getDashboard()
    {
        $data['page_title'] = 'Staff Dashboard';
        $id = Auth::id();
        $data['signal'] = Signal::wherePost_by($id)->count();
        $data['win_pips'] = Signal::wherePostBy($id)->whereWin(1)->sum('pips');
        $data['loss_pips'] = Signal::wherePostBy($id)->whereWin(2)->sum('pips');

        $data['pipsChart'] = (new SymbolPipsChart())->generate();
        $data['line'] = (new Last30DaysChart())->generate();
        return view('staff.dashboard', $data);
    }

    public function editProfile()
    {
        $data['page_title'] = "Edit Staff Profile";
        $data['admin'] = Staff::findOrFail(Auth::user()->id);
        $data['country'] = json_decode(file_get_contents(storage_path('json/country.json')), true);

        return view('staff.edit-profile', $data);
    }

    /**
     * @param Request $request
     */
    public function updateProfile(Request $request)
    {
        $admin = Staff::findOrFail(Auth::user()->id);
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:staff,email,' . $admin->id,
            'phone' => 'required|min:5|unique:staff,phone,' . $admin->id,
            'image' => 'mimes:png,jpg,jpeg',
        ]);
        $in = $request->except('_method', '_token');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = 'assets/images/' . $filename;
            Image::make($image)->resize(215, 215)->save(public_path($location));
            if ($admin->image != null) {
                $path = 'assets/images/';
                $link = $path . $admin->image;
                File::delete(public_path($link));
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

        return view('staff.change-password', $data);
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

            $user = Staff::findOrFail($c_id);

            if (Hash::check($request->current_password, $c_password)) {

                $password = Hash::make($request->password);
                $user->password = $password;
                $user->save();
                session()->flash('message', 'Password Changes Successfully.');
                session()->flash('title', 'success');

                return redirect()->back();
            } else {
                session()->flash('message', 'Current Password Not Match');
                Session::flash('type', 'warning');

                return redirect()->back();
            }

        } catch (\PDOException $e) {
            session()->flash('message', $e->getMessage());
            Session::flash('type', 'warning');
            session()->flash('title', 'Opps');

            return redirect()->back();
        }

    }

    public function signalCreate()
    {
        $data['plan'] = Plan::whereStatus(1)->get();
        $data['page_title'] = "Create New Signal";
        $data['asset'] = Asset::whereStatus(1)->get();
        $data['type'] = Type::whereStatus(1)->get();
        $data['status'] = Status::whereStatus(1)->get();
        $data['frame'] = Frame::whereStatus(1)->get();
        $data['symbol'] = Symbol::whereStatus(1)->get();
        return view('staff.signal.create', $data);
    }

    /**
     * @param Request $request
     */
    public function signalStore(Request $request)
    {
        ini_set('max_execution_time', 900);

        $request->validate([
            'title'       => 'required',
            'service_id'  => 'required',
            'description' => 'required',
            'asset_id'    => 'required',
            'symbol_id'   => 'required',
            'type_id'     => 'required',
            'frame_id'    => 'required',
            'status_id'   => 'required',
        ]);

        $basic = BasicSetting::first();

        $data['title'] = $request->title;
        $data['custom'] = strtoupper(Str::random(16));
        $data['post_by'] = Auth::user()->id;
        $data['description'] = Purify::clean($request->description);
        $data['asset_id'] = $request->asset_id;
        $data['symbol_id'] = $request->symbol_id;
        $data['type_id'] = $request->type_id;
        $data['frame_id'] = $request->frame_id;
        $data['status_id'] = $request->status_id;
        $data['profit'] = $request->profit;
        $data['profit_two'] = $request->profit_two;
        $data['profit_three'] = $request->profit_three;
        $data['loss'] = $request->loss;
        $data['entry'] = $request->entry;
        $data['plan_ids'] = implode(";", $request->service_id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = $data['custom'];
            $ext = strtolower($image->getClientOriginalExtension());
            $image_full_name = $image_name . '.' . $ext;
            $location = ('assets/images/signal') . '/' . $image_full_name;
            Image::make($image)->save(public_path($location));
            $data['image'] = $image_full_name;
        }

        $sig = Signal::create($data);
        foreach (array_reverse($request->service_id) as $s) {
            $sp = new SignalPlan;
            $sp->signal_id = $sig->id;
            $sp->plan_id = $s;
            $sp->save();

            $users = User::wherePlanStatus(1)->wherePlanId($s)->get();

            foreach ($users as $user) {
                $us['user_id'] = $user->id;
                $us['signal_id'] = $sig->id;
                $us['plan_id'] = $s;

                $us['whatsapp_alert'] = 0;
                $us['telegram_alert'] = 0;
                $us['email_alert'] = 0;
                $us['sms_alert'] = 0;

                if ($basic->whatsapp_status == 1 && $user->plan->whatsapp_status == 1 && empty(!$user->whatsapp_id)) {
                    $us['whatsapp_alert'] = 1;
                }
                if ($basic->telegram_status == 1 && $user->plan->telegram_status == 1 && empty(!$user->telegram_id)) {
                    $us['telegram_alert'] = 1;
                }
                if ($basic->email_alert == 1 && $user->plan->email_status == 1 && $user->email_status == 1) {
                    $us['email_alert'] = 1;
                }
                if ($basic->phone_alert == 1 && $user->plan->sms_status == 1 && $user->phone_status == 1) {
                    $us['sms_alert'] = 1;
                }
                if ($us['telegram_alert'] == 1 || $us['email_alert'] == 1 || $us['sms_alert'] == 1) {
                    UserSignal::create($us);
                }
            }
        }
        session()->flash('message', 'Signal Posted Successfully.');
        session()->flash('type', 'success');
        return redirect()->back();
    }

    public function signalIndex()
    {
        $data['page_title'] = "All Signal";
        $data['status'] = Status::whereStatus(1)->get();
        $data['signal'] = Signal::wherePost_by(Auth::id())
            ->with([
                'asset',
                'symbol',
                'type',
                'frame',
                'status',
            ])
            ->withCount('ratings')
            ->withSum('ratings', 'rating')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('staff.signal.index', $data);
    }

    /**
     * @param Request $request
     */
    public function signalResult(Request $request)
    {
        $request->validate([
            'signal_id' => 'required',
            'status_id' => 'required',
            'win'       => 'required',
            'pips'      => 'required|numeric',
        ]);

        $signal = Signal::findOrFail($request->input('signal_id'));
        $signal->status_id = $request->input('status_id');
        $signal->win = $request->input('win');
        $signal->pips = $request->input('pips');
        $signal->save();

        toastMessage('success', 'Signal Result updated.');
        return to_back();

        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    public function signalHome(Request $request)
    {
        $request->validate([
            'signal_id' => 'required',
        ]);
        $signal = Signal::findOrFail($request->input('signal_id'));
        $signal->home = $request->input('home');
        $signal->home_lock = $request->input('home_lock');
        $signal->save();

        toastMessage('success', 'Signal Home Status updated.');
        return to_back();
    }

    /**
     * @param $id
     */
    public function signalShow($id)
    {
        $data['page_title'] = "View Signal";
        $data['signal'] = $signal = Signal::whereCustom($id)->firstOrFail();
        $data['total_comment'] = SignalComment::whereSignal_id($signal->id)->count();
        $data['comments'] = SignalComment::whereSignal_id($signal->id)->get();
        $data['total_rating'] = SignalRating::whereSignal_id($signal->id)->count();
        $data['sum_rating'] = SignalRating::whereSignal_id($signal->id)->sum('rating');
        $data['plans'] = Plan::whereIn('id', explode(';', $signal->plan_ids))->select('name')->get();
        if ($data['total_rating'] == 0) {
            $data['final_rating'] = 0;
        } else {
            $data['final_rating'] = round($data['sum_rating'] / $data['total_rating']);
        }
        $data['rating'] = SignalRating::whereSignal_id($signal->id)->get();
        $data['status'] = Status::all();
        $data['user_rating'] = SignalRating::whereSignal_id($signal->id)->whereUser_id(0)->first();

        return view('staff.signal.view', $data);
    }

    /**
     * @param $id
     */
    public function signalEdit($id)
    {
        $data['page_title'] = "Edit Signal";
        $data['signal'] = Signal::whereCustom($id)->firstOrFail();
        $data['plan'] = Plan::whereStatus(1)->get();
        $ss = $data['signal']->plan_ids;
        $data['signalPlan'] = explode(';', $ss);
        $data['asset'] = Asset::whereStatus(1)->get();
        $data['type'] = Type::whereStatus(1)->get();
        $data['status'] = Status::whereStatus(1)->get();
        $data['frame'] = Frame::whereStatus(1)->get();
        $data['symbol'] = Symbol::whereStatus(1)->get();

        return view('staff.signal.edit', $data);
    }

    /**
     * @param Request $request
     */
    public function signalUpdate(Request $request)
    {
        $sig = Signal::findOrFail($request->signal_id);

        ini_set('max_execution_time', 900);

        $request->validate([
            'title'       => 'required',
            'service_id'  => 'required',
            'description' => 'required',
            'asset_id'    => 'required',
            'symbol_id'   => 'required',
            'type_id'     => 'required',
            'frame_id'    => 'required',
            'status_id'   => 'required',
        ]);

        $basic = BasicSetting::first();
        $data['title'] = $request->title;
        $data['custom'] = strtoupper(Str::random(16));
        $data['description'] = Purify::clean($request->description);
        $data['asset_id'] = $request->asset_id;
        $data['symbol_id'] = $request->symbol_id;
        $data['type_id'] = $request->type_id;
        $data['frame_id'] = $request->frame_id;
        $data['status_id'] = $request->status_id;
        $data['profit'] = $request->profit;
        $data['profit_two'] = $request->profit_two;
        $data['profit_three'] = $request->profit_three;
        $data['loss'] = $request->loss;
        $data['entry'] = $request->entry;
        $data['plan_ids'] = implode(";", $request->service_id);

        if ($request->hasFile('image')) {
            File::delete(public_path(('assets/images/signal') . '/' . $sig->getRawOriginal('image')));
            $image = $request->file('image');
            $ext = strtolower($image->getClientOriginalExtension());
            $image_full_name = $data['custom'] . '.' . $ext;
            $location = ('assets/images/signal') . '/' . $image_full_name;
            Image::make($image)->save(public_path($location));
            $data['image'] = $image_full_name;
        }

        $sig->update($data);

        SignalPlan::whereSignalId($sig->id)->delete();
        UserSignal::whereSignalId($sig->id)->delete();

        foreach (array_reverse($request->service_id) as $s) {

            $sp = new SignalPlan();
            $sp->signal_id = $sig->id;
            $sp->plan_id = $s;
            $sp->save();

            $users = User::wherePlan_status(1)->wherePlan_id($s)->get();

            foreach ($users as $user) {

                $us['user_id'] = $user->id;
                $us['signal_id'] = $sig->id;
                $us['plan_id'] = $s;

                $us['whatsapp_alert'] = 0;
                $us['telegram_alert'] = 0;
                $us['email_alert'] = 0;
                $us['sms_alert'] = 0;

                if ($basic->whatsapp_status == 1 && $user->plan->whatsapp_status == 1 && empty(!$user->whatsapp_id)) {
                    $us['whatsapp_alert'] = 1;
                }
                if ($basic->telegram_status == 1 && $user->plan->telegram_status == 1 && empty(!$user->telegram_id)) {
                    $us['telegram_alert'] = 1;
                }
                if ($basic->email_alert == 1 && $user->plan->email_status == 1 && $user->email_status == 1) {
                    $us['email_alert'] = 1;
                }
                if ($basic->phone_alert == 1 && $user->plan->sms_status == 1 && $user->phone_status == 1) {
                    $us['sms_alert'] = 1;
                }

                if ($us['telegram_alert'] == 1 || $us['email_alert'] == 1 || $us['sms_alert'] == 1) {
                    UserSignal::create($us);
                }
            }
        }

        session()->flash('message', 'Signal Update Successfully.');
        session()->flash('type', 'success');
        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    public function signalDestroy(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $signal = Signal::findOrFail($request->id);

        SignalPlan::whereSignal_id($signal->id)->delete();
        SignalComment::whereSignal_id($signal->id)->delete();
        SignalRating::whereSignal_id($signal->id)->delete();
        UserSignal::whereSignal_id($signal->id)->delete();
        File::delete(public_path(('assets/images/signal') . '/' . $signal->getRawOriginal('image')));
        $signal->delete();
        session()->flash('message', 'Signal Deleted Successfully.');
        session()->flash('type', 'success');

        return to_route('signal-all');
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
        $in['user_id'] = -1;
        SignalComment::create($in);
        session()->flash('message', 'Comment Submitted Successfully.');
        session()->flash('type', 'success');

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
        $in['user_id'] = -1;
        SignalRating::create($in);
        \session()->flash('message', 'Rating Submitted Successfully.');
        \session()->flash('type', 'success');

        return redirect()->back();
    }

    public function createPost()
    {
        $data['category'] = Category::whereStatus(1)->get();
        $data['page_title'] = "Create New Post";

        return view('staff.post.create', $data);
    }

    /**
     * @param Request $request
     */
    public function storePost(Request $request)
    {
        $request->validate([
            'title'       => 'required|max:60|unique:posts,title',
            'category'    => 'required',
            'image'       => 'required|mimes:png,jpeg,jpg',
            'tags'        => 'required',
            'description' => 'required',
        ]);

        $data['user_id'] = Auth::id();
        $data['user_type'] = 1;
        $data['category_id'] = $request->category;
        $data['title'] = $request->title;
        $data['slug'] = str_slug($request->title);
        $data['tags'] = $request->tags;
        $data['description'] = $request->description;
        $data['fetured'] = $request->fetured == 'on' ? '1' : '0';
        $data['status'] = $request->status == 'on' ? '1' : '0';

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = str_random(20);
            $ext = strtolower($image->getClientOriginalExtension());
            $image_full_name = $image_name . '.' . $ext;
            $location = ('assets/images/post') . '/' . $image_full_name;
            Image::make($image)->resize(800, 540)->save(public_path($location));
            $data['image'] = $image_full_name;
        }

        Post::create($data);
        session()->flash('message', 'Post Created Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    public function indexPost()
    {
        $data['page_title'] = "All Post";
        $data['testimonial'] = Post::whereUserType(1)
            ->whereUserId(Auth::id())
            ->with('category')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('staff.post.index', $data);
    }

    /**
     * @param $id
     */
    public function editPost($id)
    {
        $data['category'] = Category::whereStatus(1)->get();
        $data['page_title'] = "Edit Post";
        $data['testimonial'] = Post::findOrFail($id);

        return view('staff.post.edit', $data);
    }

    /**
     * @param Request $request
     */
    public function updatePost(Request $request)
    {
        $r = Post::find($request->id);
        $request->validate([
            'title' => 'required|max:60|unique:posts,title,' . $r->id,
            'image' => 'mimes:png,jpeg,jpg',
        ]);

        $data['category_id'] = $request->category;
        $data['title'] = $request->title;
        $data['slug'] = str_slug($request->title);
        $data['tags'] = $request->tags;
        $data['description'] = $request->description;
        $data['fetured'] = $request->fetured == 'on' ? '1' : '0';
        $data['status'] = $request->status == 'on' ? '1' : '0';
        if ($request->hasFile('image')) {
            File::delete(public_path(('assets/images/post') . '/' . $r->image));
            $image = $request->file('image');
            $image_name = str_random(20);
            $ext = strtolower($image->getClientOriginalExtension());
            $image_full_name = $image_name . '.' . $ext;
            $location = ('assets/images/post') . '/' . $image_full_name;
            Image::make($image)->resize(800, 540)->save(public_path($location));
            $data['image'] = $image_full_name;
        }
        $r->update($data);
        session()->flash('message', 'Post Update Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    public function destroyPost(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $testimonial = Post::findOrFail($request->id);
        File::delete(public_path(('assets/images/post') . '/' . $testimonial->image));
        $testimonial->delete();
        session()->flash('message', 'Post Deleted Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    public function publishPost(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $data = Post::findOrFail($request->id);
        if ($data->status == 1) {
            $data->status = 0;
            $data->save();

            session()->flash('message', 'Post Unpublish Successfully.');
            Session::flash('type', 'success');
            Session::flash('title', 'Success');
        } else {
            $data->status = 1;
            $data->save();

            session()->flash('message', 'Post Publish Successfully.');
            Session::flash('type', 'success');
            Session::flash('title', 'Success');
        }

        return redirect()->back();
    }

    public function userList()
    {
        $data['page_title'] = "User List";
        $data['user'] = User::orderBy('id', 'desc')->paginate(15);

        return view('staff.user-list', $data);
    }

    /**
     * @param Request $request
     */
    public function updateSignalResult(Request $request)
    {
        $request->validate([
            'id'     => 'required',
            'status' => 'required',
        ]);

        $signal = Signal::findOrFail($request->id);
        $signal->status = $request->status;
        $signal->save();
        session()->flash('message', 'Signal Result Updated.');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    public function createUser()
    {
        $data['page_title'] = 'Add new User';
        $data['plan'] = Plan::whereStatus(1)->get();
        $data['country'] = json_decode(file_get_contents(storage_path('json/country.json')), true);

        return view('staff.user-create', $data);
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

        return view('staff.user-edit', $data);
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
                    $btn = '<a href="' . route('staff-user-edit', $row->id) . '" class="btn btn-primary btn-mini bold uppercase" title="Edit"><i class="fa fa-edit"></i> Edit</a>';
                    $btn .= ' <button type="button" class="btn btn-danger btn-mini bold uppercase confirm_button" data-toggle="modal" data-target="#ConModal" data-id="' . $row->id . '" title="Delete"> <i class="fa fa-trash"></i> Delete </button>';

                    return $btn;
                })
                ->rawColumns(['user_details', 'email_status', 'phone_status', 'status', 'plan_status', 'action'])
                ->make(true);
        }

        return view('staff.manage-user', $data);
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

    public function allWithdrawRequest()
    {
        $data['page_title'] = 'All Withdraw Request';
        $data['log'] = WithdrawLog::latest()->paginate(15);

        return view('staff.withdraw-request', $data);
    }

    /**
     * @param $custom
     */
    public function withdrawRequestView($custom)
    {
        $data['page_title'] = $custom . ' - Withdraw Request';
        $data['withdraw'] = WithdrawLog::whereCustom($custom)->first();
        return view('staff.withdraw-request-view', $data);
    }

    /**
     * @param Request $request
     */
    public function WithdrawRefund(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $withdraw = WithdrawLog::findOrFail($request->id);
        $user = User::findOrFail($withdraw->user_id);

        $tr['custom'] = $withdraw->custom;
        $tr['user_id'] = $withdraw->user_id;
        $tr['type'] = 5;
        $tr['balance'] = $withdraw->amount;
        $tr['post_balance'] = $user->balance + $withdraw->amount;
        $tr['details'] = 'Withdraw Refund For ' . $withdraw->withdrawMethod->name;
        TransactionLog::create($tr);

        $tr['custom'] = $tr['custom'] = $withdraw->custom;
        $tr['user_id'] = $user->id;
        $tr['type'] = 6;
        $tr['balance'] = $withdraw->charge;
        $tr['post_balance'] = $user->balance + $withdraw->charge + $withdraw->amount;
        $tr['details'] = 'Withdraw Charge Refund ' . $withdraw->withdrawMethod->name;
        TransactionLog::create($tr);

        $user->balance = $user->balance + $withdraw->amount + $withdraw->charge;
        $user->save();

        $withdraw->status = 2;
        $withdraw->save();
        session()->flash('message', 'Withdraw Refund Accept.');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    public function WithdrawConfirm(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $withdraw = WithdrawLog::findOrFail($request->id);
        $withdraw->status = 1;
        $withdraw->save();
        session()->flash('message', 'Withdraw Confirmed Successfully.');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    public function getManualPaymentRequest()
    {
        $data['page_title'] = "Manual Payment Request";
        $manualPaymentIds = PaymentMethod::whereType(1)->pluck('id')->toArray();
        $data['payment'] = PaymentLog::with([
            'user:id,name,country_code,phone,email',
            'paymentmethod:id,name',
            'plan:id,name',
        ])->whereIn('payment_id', $manualPaymentIds)->orderBy('id', 'desc')->get();

        return view('staff.manual-request', $data);
    }

    /**
     * @param $custom
     */
    public function viewManualPaymentRequest($custom)
    {
        $data['page_title'] = $custom . " - Manual Payment View";
        $data['payment'] = PaymentLog::whereOrder_number($custom)->first();
        return view('staff.manual-request-view', $data);
    }

    /**
     * @param Request $request
     */
    public function cancelManualPaymentRequest(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $pay = PaymentLog::findOrFail($request->id);
        $pay->status = 2;
        $pay->save();
        session()->flash('message', 'Payment Request Cancel.');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    public function confirmManualPaymentRequest(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $data = PaymentLog::findOrFail($request->id);

        (new PaymentAction($data))->perform();

        session()->flash('message', 'Payment Request Complete.');
        session()->flash('type', 'success');

        return redirect()->back();
    }
}
