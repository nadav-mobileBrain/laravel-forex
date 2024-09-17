<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PaymentLog;
use App\Models\BasicSetting;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\TransactionLog;
use App\Models\PaymentLogImage;
use App\TraitsFolder\CommonTrait;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\Payment\PaymentAction;

class PaymentController extends Controller
{
    use CommonTrait;
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function paymentMethod()
    {
        $data['page_title'] = 'Payment Gateway';
        $data['payments'] = PaymentMethod::whereType(0)->get();

        return view('gateway.automated', $data);
    }

    /**
     * @param $id
     */
    public function editMethod($id)
    {
        $data['page_title'] = 'Edit Payment Gateway';
        $data['payment'] = PaymentMethod::findOrFail($id);
        $data['currencies'] = json_decode(file_get_contents(storage_path('json/currency.json')), true);

        return view("gateway.edit.gateway$id", $data);
    }

    /**
     * @param Request $request
     */
    public function updatePaymentMethod(Request $request, $id)
    {

        $payment = PaymentMethod::findOrFail($id);
        $request->validate([
            'name'  => 'required',
            'rate'  => 'required|numeric',
            'image' => 'nullable|mimes:png,jpg,jpeg',
            'val1'  => 'required',
        ]);

        $payment->name = $request->name;
        if ($request->has('rate')) {
            $payment->rate = $request->rate;
        }if ($request->has('val1')) {
            $payment->val1 = $request->val1;
        }if ($request->has('val2')) {
            $payment->val2 = $request->val2;
        }if ($request->has('val3')) {
            $payment->val3 = $request->val3;
        }if ($request->has('currency')) {
            $payment->currency = $request->currency;
        }if ($request->has('extra')) {
            $payment->extra = $request->extra;
        }

        $payment->status = $request->status == 'on' ? '1' : '0';

        if ($request->hasFile('image')) {
            $image3 = $request->file('image');
            $filename3 = 'gateway_' . time() . '.' . $image3->getClientOriginalExtension();
            $location = ('assets/images/payment') . '/' . $filename3;
            Image::make($image3)->resize(290, 190)->save($location);
            File::delete(('assets/images/payment') . '/' . $payment->image);
            $payment->image = $filename3;
        }
        $payment->save();

        session()->flash('message', "{$payment->name} Gateway Updated Successfully.");
        session()->flash('type', 'success');

        return redirect()->back();
    }

    public function getManualPaymentMethod()
    {
        $data['page_title'] = "Manual Payment Gateway";
        $data['payment'] = PaymentMethod::whereType(1)->get();

        return view('gateway.manual', $data);
    }

    public function createManualPaymentMethod()
    {
        $data['page_title'] = "Create Payment Gateway";

        return view('gateway.manual-create', $data);
    }

    /**
     * @param Request $request
     */
    public function storeManualPaymentMethod(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'val1'  => 'required',
            'rate'  => 'required|numeric',
            'image' => 'required|mimes:png,jpg,jpeg,gif',
        ]);

        $in = $request->except('_method', '_token');
        $in['type'] = 1;
        $in['status'] = $request->status == 'on' ? 1 : 0;
        if ($request->hasFile('image')) {
            $image3 = $request->file('image');
            $filename3 = 'manual_' . time() . 'h8' . '.' . $image3->getClientOriginalExtension();
            $location = ('assets/images/payment') . '/' . $filename3;
            Image::make($image3)->resize(290, 190)->save($location);
            $in['image'] = $filename3;
        }
        PaymentMethod::create($in);
        session()->flash('message', 'Manual Gateway Created Successfully.');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    /**
     * @param $id
     */
    public function editManualPaymentMethod($id)
    {
        $data['page_title'] = "Edit Payment Gateway";
        $data['payment'] = PaymentMethod::findOrFail($id);

        return view('gateway.manual-edit', $data);
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function updateManualPaymentMethod(Request $request, $id)
    {
        $payment = PaymentMethod::findOrFail($id);
        $request->validate([
            'name'  => 'required',
            'val1'  => 'required',
            'rate'  => 'required|numeric',
            'image' => 'mimes:png,jpg,jpeg,gif',
        ]);

        $in = $request->except('_method', '_token');
        $in['type'] = 1;
        $in['status'] = $request->status == 'on' ? 1 : 0;
        if ($request->hasFile('image')) {
            File::delete("./assets/images/payment/$payment->image");
            $image3 = $request->file('image');
            $filename3 = 'manual_' . time() . 'h8' . '.' . $image3->getClientOriginalExtension();
            $location = ('assets/images/payment') . '/' . $filename3;
            Image::make($image3)->resize(290, 190)->save($location);
            $in['image'] = $filename3;
        }
        $payment->update($in);
        session()->flash('message', 'Manual Gateway Update Successfully.');
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
        return view('gateway.manual-request', $data);
    }

    /**
     * @param $custom
     */
    public function viewManualPaymentRequest($custom)
    {
        $data['page_title'] = $custom . " - Manual Payment View";
        $data['payment'] = PaymentLog::whereOrder_number($custom)->first();

        return view('gateway.manual-request-view', $data);
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

    /**
     * @param $data
     * @param $user
     */
    public function referralAmount($data, $user)
    {
        $basic = BasicSetting::first();
        if ($basic->referral_commission_status) {
            if (User::whereId($user->parent_id)->exists()) {
                $refUser = User::findOrFail($user->parent_id);
                $amo = round((($data->amount * $basic->referral_commission_percentage) / 100), 2);
                $refUser->balance += $amo;
                $refUser->save();
                $this->transactionLog($data->order_number, $refUser->id, 7, $amo, $user->username . " - Reference Bonus");
            } else {
                $user->parent_id = 0;
                $user->save();
            }
        }
    }

    /**
     * @param $custom
     * @param $id
     * @param $type
     * @param $amount
     * @param $details
     */
    public function transactionLog($custom, $id, $type, $amount, $details)
    {
        $tr['custom'] = $custom;
        $tr['user_id'] = $id;
        $tr['type'] = $type;
        $tr['balance'] = $amount;
        $tr['post_balance'] = 0;
        $tr['details'] = $details;
        TransactionLog::create($tr);
    }

    /**
     * @param Request $request
     */
    public function deleteManualPaymentRequest(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $log = PaymentLog::findOrFail($request->id);
        foreach ($log->paymentLogImage as $img) {
            File::delete("./assets/images/paymentimage/$img->name");
            $img->delete();
        }
        $log->delete();
        session()->flash('message', 'Payment Request Deleted.');
        session()->flash('type', 'success');

        return redirect()->back();
    }

}
