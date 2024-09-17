<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Type;
use App\Models\User;
use App\Models\Asset;
use App\Models\Frame;
use App\Models\Signal;
use App\Models\Status;
use App\Models\Symbol;
use App\Models\SignalPlan;
use App\Models\UserSignal;
use Illuminate\Support\Str;
use App\Models\BasicSetting;
use App\Models\SignalRating;
use Illuminate\Http\Request;
use App\Models\SignalComment;
use App\TraitsFolder\CommonTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Stevebauman\Purify\Facades\Purify;

class SignalController extends Controller
{
    use CommonTrait;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function create()
    {
        $data['page_title'] = "Create New Signal";
        $data['asset'] = Asset::whereStatus(1)->get();
        $data['type'] = Type::whereStatus(1)->get();
        $data['status'] = Status::whereStatus(1)->get();
        $data['frame'] = Frame::whereStatus(1)->get();
        $data['symbol'] = Symbol::whereStatus(1)->get();
        $data['plan'] = Plan::whereStatus(1)->get();
        return view('signal.create', $data);
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        ini_set('max_execution_time', -1);

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

    public function index()
    {
        $data['page_title'] = "All Signal";
        $data['signal'] = Signal::with([
            'asset',
            'symbol',
            'type',
            'frame',
            'status',
        ])->withCount('ratings')
            ->withSum('ratings', 'rating')
            ->orderBy('id', 'desc')
            ->paginate(20);
        $data['status'] = Status::all();

        return view('signal.index', $data);
    }

    /**
     * @param Request $request
     */
    public function updateResult(Request $request)
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

    /**
     * @param $id
     */
    public function show($id)
    {
        $data['page_title'] = "View Signal";
        $data['signal'] = $signal = Signal::findOrFail($id);
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

        return view('signal.view', $data);
    }

    /**
     * @param $id
     */
    public function edit($id)
    {
        $data['page_title'] = "Edit Signal";
        $data['signal'] = Signal::findOrFail($id);
        $data['plan'] = Plan::whereStatus(1)->get();
        $ss = $data['signal']->plan_ids;
        $data['signalPlan'] = explode(';', $ss);
        $data['asset'] = Asset::whereStatus(1)->get();
        $data['type'] = Type::whereStatus(1)->get();
        $data['status'] = Status::whereStatus(1)->get();
        $data['frame'] = Frame::whereStatus(1)->get();
        $data['symbol'] = Symbol::whereStatus(1)->get();

        return view('signal.edit', $data);
    }

    /**
     * @param Request $request
     */
    public function update(Request $request)
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
    public function destroy(Request $request)
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
    public function result(Request $request)
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
    }

    /**
     * @param Request $request
     */
    public function home(Request $request)
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
}
