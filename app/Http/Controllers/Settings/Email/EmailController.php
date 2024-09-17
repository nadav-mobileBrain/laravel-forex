<?php

namespace App\Http\Controllers\Settings\Email;

use App\Models\EmailDriver;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class EmailController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $data['page_title'] = 'Email Drivers';
        $data['drivers'] = EmailDriver::all();
        return view('settings.email.index', $data);
    }

    /**
     * @param $id
     */
    public function edit($id)
    {
        $data['page_title'] = 'Edit Email Driver';
        $data['driver'] = EmailDriver::findOrFail($id);
        return view('settings.email.' . $data['driver']->driver, $data);
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id)
    {
        $driver = EmailDriver::findOrFail($id);
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
            $location = ('assets/images/settings/email') . '/' . $filename3;
            Image::make($image3)->resize(192, 144)->save($location);
            File::delete(('assets/images/settings/email') . '/' . $driver->image);
            $driver->image = $filename3;
        }
        $driver->save();

        toastMessage('success', 'Email Driver updated Successfully.');
        return to_back();
    }

    /**
     * @param Request $request
     */
    public function test(Request $request)
    {
        $request->validate([
            'driver' => 'required',
            'email'  => 'required|email',
            'name'   => 'required',
        ]);

        $driver = EmailDriver::whereDriver($request->input('driver'))->firstOrFail();

        $payload['email'] = $request->input('email');
        $payload['name'] = $request->input('name');
        $payload['subject'] = 'Email Driver Testing';
        $payload['view'] = 'emails.email-driver';
        $payload['viewData'] = [
            'name' => $request->input('name'),
            'body' => 'This is the body of test email. Now you cam enable this driver',
        ];

        $action = (new EmailGlobalController($driver))->send($payload);

        if ($action['success']) {
            toastMessage('success', 'Email function have no issue, now if you found the email then you can enable the driver.');
        } else {
            $message = trim(preg_replace('/\s+/', ' ', $action['message']));
            toastMessage('warning', $message);
        }
        return to_back();

    }
}
