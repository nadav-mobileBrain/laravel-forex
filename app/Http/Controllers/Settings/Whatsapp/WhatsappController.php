<?php

namespace App\Http\Controllers\Settings\Whatsapp;

use Illuminate\Http\Request;
use App\Models\WhatsappDriver;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class WhatsappController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $data['page_title'] = 'Whatsapp Drivers';
        $data['drivers'] = WhatsappDriver::all();
        return view('settings.whatsapp.index', $data);
    }

    /**
     * @param $id
     */
    public function edit($id)
    {
        $data['page_title'] = 'Whatsapp Drivers';
        $data['driver'] = WhatsappDriver::findOrFail($id);
        return view('settings.whatsapp.' . $data['driver']->driver, $data);
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id)
    {
        $driver = WhatsappDriver::findOrFail($id);
        $request->validate([
            'name'   => 'required',
            'image'  => 'nullable|mimes:png,jpg,jpeg',
            'data'   => 'required|array',
            'data.*' => 'required',
        ]);

        $driver->name = $request->input('name');
        $driver->status = $request->input('status') == 'on' ? true : false;
        $driver->data = $request->input('data');
        if ($request->hasFile('image')) {
            $image3 = $request->file('image');
            $filename3 = $driver->driver . '_' . time() . '.' . $image3->getClientOriginalExtension();
            $location = ('assets/images/settings/whatsapp') . '/' . $filename3;
            Image::make($image3)->resize(192, 144)->save($location);
            File::delete(('assets/images/settings/whatsapp') . '/' . $driver->image);
            $driver->image = $filename3;
        }
        $driver->save();

        toastMessage('success', 'Whatsapp Driver updated Successfully.');
        return to_back();
    }

    /**
     * @param Request $request
     */
    public function test(Request $request)
    {
        $request->validate([
            'driver' => 'required',
            'number' => 'required',
        ]);

        $driver = WhatsappDriver::whereDriver($request->input('driver'))->firstOrFail();

        $payload['number'] = $request->input('number');
        $payload['message'] = "Hey this is test message for driver testing. If you receive this message then now you can enable this driver.";

        $action = (new WhatsappGlobalController($driver))->send($payload);

        if ($action['success']) {
            toastMessage('success', 'Whatsapp function have no issue, now if you receive the message then you can enable the driver.');
        } else {
            $message = trim(preg_replace('/\s+/', ' ', $action['message']));
            toastMessage('warning', $message);
        }
        return to_back();
    }
}
