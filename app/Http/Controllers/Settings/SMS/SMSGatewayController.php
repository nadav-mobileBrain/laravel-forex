<?php

namespace App\Http\Controllers\Settings\SMS;

use App\Models\SmsGateway;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\Settings\SMS\SMSGlobalController;

class SMSGatewayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $data['page_title'] = 'SMS Gateway';
        $data['gateways'] = SmsGateway::all();
        return view('settings.sms.index', $data);
    }

    /**
     * @param $id
     */
    public function edit($id)
    {
        $data['page_title'] = 'Edit SMS Gateway';
        $data['gateway'] = SmsGateway::findOrFail($id);
        return view('settings.sms.' . $data['gateway']->driver, $data);
    }

    /**
     * @param Request $request
     */
    public function update(Request $request, $id)
    {
        $gateway = SmsGateway::findOrFail($id);
        $request->validate([
            'name'   => 'required',
            'image'  => 'nullable|mimes:png,jpg,jpeg',
            'data'   => 'required|array',
            'data.*' => 'required',
        ]);

        $gateway->name = $request->input('name');
        $gateway->status = $request->input('status') == 'on' ? true : false;
        $gateway->data = $request->input('data');
        if ($request->hasFile('image')) {
            $image3 = $request->file('image');
            $filename3 = $gateway->driver . '_' . time() . '.' . $image3->getClientOriginalExtension();
            $location = ('assets/images/settings/sms') . '/' . $filename3;
            Image::make($image3)->resize(192, 144)->save($location);
            File::delete(('assets/images/settings/sms') . '/' . $gateway->image);
            $gateway->image = $filename3;
        }
        $gateway->save();

        toastMessage('success', 'SMS Gateway updated Successfully.');
        return to_back();
    }

    /**
     * @param Request $request
     */
    public function test(Request $request)
    {
        $request->validate([
            'driver' => 'required',
            'phone'  => 'required|numeric',
        ]);

        $gateway = SmsGateway::whereDriver($request->input('driver'))->firstOrFail();
        $phone = $request->input('phone');
        $message = 'Dummy SMS for testing.';

        $action = (new SMSGlobalController($gateway))->send($phone, $message);

        if ($action['success']) {
            toastMessage('success', 'Function have no issue. Now if you receive sms then change gateway status to active.');
        } else {
            toastMessage('warning', $action['message'] ?? "Something went wrong");
        }
        return to_back();
    }
}
