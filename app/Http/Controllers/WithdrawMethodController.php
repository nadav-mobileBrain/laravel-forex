<?php

namespace App\Http\Controllers;

use App\Models\WithdrawMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class WithdrawMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function createMethod()
    {
        $data['page_title'] = "Create Withdraw Method";

        return view('withdraw.withdraw-method-create', $data);
    }

    /**
     * @param Request $request
     */
    public function storeMethod(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'image'        => 'required|mimes:png,jpeg,jpg',
            'charge'       => 'required|numeric',
            'withdraw_min' => 'required|numeric',
            'withdraw_max' => 'required|numeric',
            'duration'     => 'required|numeric'
        ]);
        $in = $request->except('_method', '_token');
        $in['status'] = $request->status == 'on' ? '1' : '0';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'withdraw_method' . time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('assets/images/withdraw/' . $filename);
            Image::make($image)->resize(290, 190)->save($location);
            $in['image'] = $filename;
        }
        WithdrawMethod::create($in);
        session()->flash('message', 'Withdraw method Created Successfully.');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    public function allMethod()
    {
        $data['page_title'] = 'All Withdraw Gateway';
        $data['withdraw'] = WithdrawMethod::all();

        return view('withdraw.withdraw-method', $data);
    }

    /**
     * @param $id
     */
    public function editMethod($id)
    {
        $data['page_title'] = "Edit Withdraw Gateway";
        $data['withdraw'] = WithdrawMethod::findOrFail($id);

        return view('withdraw.withdraw-method-edit', $data);
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function updateMethod(Request $request, $id)
    {
        $withdraw = WithdrawMethod::findOrFail($id);
        $request->validate([
            'name'         => 'required',
            'image'        => 'mimes:png,jpeg,jpg',
            'charge'       => 'required|numeric',
            'withdraw_min' => 'required|numeric',
            'withdraw_max' => 'required|numeric',
            'duration'     => 'required|numeric'
        ]);
        $in = $request->except('_method', '_token');
        $in['status'] = $request->status == 'on' ? '1' : '0';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'withdraw_method' . time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('assets/images/withdraw/' . $filename);
            Image::make($image)->resize(290, 190)->save($location);
            $in['image'] = $filename;
            $oldImage = public_path('assets/images/withdraw/' . $withdraw->image);
            File::delete($oldImage);
        }

        $withdraw->update($in);
        session()->flash('message', 'Withdraw method Update Successfully.');
        session()->flash('type', 'success');

        return redirect()->back();
    }

}
