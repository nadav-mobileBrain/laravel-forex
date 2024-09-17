<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function create()
    {
        $data['page_title'] = "Create New Plan";

        return view('plan.create', $data);
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {

        $request->validate([
            'name'       => 'required|unique:plans,name',
            'plan_type'  => 'required',
            'duration'   => 'required_if:plan_type,0|nullable|numeric',
            'price_type' => 'required',
            'price'      => 'required_if:price_type,1|nullable|numeric',
            'support'    => 'required',
        ]);
        //dd($request->all());
        $in = $request->except('_method', '_token');
        $in['whatsapp_status'] = $request->whatsapp_status == 'on' ? '1' : '0';
        $in['telegram_status'] = $request->telegram_status == 'on' ? '1' : '0';
        $in['email_status'] = $request->email_status == 'on' ? '1' : '0';
        $in['call_status'] = $request->call_status == 'on' ? '1' : '0';
        $in['coaching_status'] = $request->coaching_status == 'on' ? '1' : '0';
        $in['sms_status'] = $request->sms_status == 'on' ? '1' : '0';
        $in['dashboard_status'] = $request->dashboard_status == 'on' ? '1' : '0';
        $in['status'] = $request->status == 'on' ? '1' : '0';
        if ($request->filled('contents')) {
            $in['contents'] = $request->input('contents');
        } else {
            $in['contents'] = [];
        }

        Plan::create($in);

        session()->flash('message', 'Plan Created Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    public function index()
    {
        $data['page_title'] = "All Plan";
        $data['plan'] = Plan::all();

        return view('plan.index', $data);
    }

    /**
     * @param $id
     */
    public function edit($id)
    {
        $data['page_title'] = "Edit Plan";
        $data['plan'] = Plan::findOrFail($id);

        return view('plan.edit', $data);
    }

    /**
     * @param Request $request
     */
    public function update(Request $request, $id)
    {
        $r = Plan::find($id);
        $request->validate([
            'name'       => 'required|unique:plans,name,' . $r->id,
            'plan_type'  => 'required',
            'duration'   => 'required_if:plan_type,0|nullable|numeric',
            'price_type' => 'required',
            'price'      => 'required_if:price_type,1|nullable|numeric',
            'support'    => 'required',
        ]);

        $in = $request->except('_method', '_token', 'id');
        $in['whatsapp_status'] = $request->whatsapp_status == 'on' ? '1' : '0';
        $in['telegram_status'] = $request->telegram_status == 'on' ? '1' : '0';
        $in['email_status'] = $request->email_status == 'on' ? '1' : '0';
        $in['call_status'] = $request->call_status == 'on' ? '1' : '0';
        $in['coaching_status'] = $request->coaching_status == 'on' ? '1' : '0';
        $in['sms_status'] = $request->sms_status == 'on' ? '1' : '0';
        $in['dashboard_status'] = $request->dashboard_status == 'on' ? '1' : '0';
        $in['status'] = $request->status == 'on' ? '1' : '0';
        if ($request->filled('contents')) {
            $in['contents'] = $request->input('contents');
        } else {
            $in['contents'] = [];
        }
        $r->update($in);
        session()->flash('message', 'Plan Update Successfully.');
        Session::flash('type', 'success');

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
        $testimonial = Plan::findOrFail($request->id);
        $testimonial->delete();
        session()->flash('message', 'Plan Deleted Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

}
