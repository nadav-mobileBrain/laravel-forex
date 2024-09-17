<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Admin;
use App\Mail\VerifySMTP;
use App\Models\BasicSetting;
use App\Models\EmailSetting;
use Illuminate\Http\Request;
use App\Models\ReferralLevel;
use App\Models\DatabaseBackup;
use App\Models\TelegramDriver;
use App\TraitsFolder\CommonTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use App\TraitsFolder\DatabaseBackupTrait;

class BasicSettingController extends Controller
{
    use DatabaseBackupTrait, CommonTrait;
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function getChangePass()
    {
        $data['page_title'] = "Change Password";

        return view('dashboard.change-password', $data);
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
            $c_password = Auth::guard('admin')->user()->password;
            $c_id = Auth::guard('admin')->user()->id;

            $user = Admin::findOrFail($c_id);

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

    public function getBasicSetting()
    {
        $data['page_title'] = "Basic Setting";

        return view('basic.basic-setting', $data);
    }

    /**
     * @param Request $request
     * @param $id
     */
    protected function putBasicSetting(Request $request, $id)
    {
        $basic = BasicSetting::findOrFail($id);
        $this->validate($request, [
            'title'       => 'required',
            'phone'       => 'required',
            'email'       => 'required',
            'address'     => 'required',
            'meta_tag'    => 'required',
            'author'      => 'required',
            'description' => 'required',
        ]);
        $in = $request->except(['_method', '_token']);
        $in['email_verify'] = $request->email_verify == 'on' ? '1' : '0';
        $in['phone_verify'] = $request->phone_verify == 'on' ? '1' : '0';
        $in['whatsapp_status'] = $request->whatsapp_status == 'on' ? '1' : '0';
        $in['telegram_status'] = $request->telegram_status == 'on' ? '1' : '0';
        $in['email_alert'] = $request->email_alert == 'on' ? '1' : '0';
        $in['phone_alert'] = $request->phone_alert == 'on' ? '1' : '0';
        $basic->fill($in)->save();
        session()->flash('message', 'Basic Setting Updated Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    public function editProfile()
    {
        $data['page_title'] = "Edit Admin Profile";
        $data['admin'] = Admin::findOrFail(Auth::user()->id);

        return view('dashboard.edit-profile', $data);
    }

    /**
     * @param Request $request
     */
    public function updateProfile(Request $request)
    {
        $admin = Admin::findOrFail(Auth::user()->id);
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:admins,email,' . $admin->id,
            'username' => 'required|min:5|unique:admins,username,' . $admin->id,
            'image'    => 'mimes:png,jpg,jpeg',
        ]);
        $in = $request->except('_method', '_token');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = 'assets/images/' . $filename;
            Image::make($image)->resize(215, 215)->save($location);
            if ($admin->image != 'admin-default.png') {
                $path = './assets/images/';
                $link = $path . $admin->image;
                File::delete($link);
            }
            $in['image'] = $filename;
        }
        $admin->fill($in)->save();
        session()->flash('message', 'Profile Updated Successfully.');
        session()->flash('title', 'Success');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    public function referralSetting()
    {
        $data['page_title'] = 'Referral Setting';
        $data['total_level'] = ReferralLevel::count();
        $data['levels'] = ReferralLevel::all();

        return view('basic.referral-setting', $data);
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function referralSettingUpdate(Request $request, $id)
    {

        $request->validate([
            'total_level'  => 'required|numeric',
            'commission'   => 'required|array',
            'commission.*' => 'required|numeric',
        ]);

        $basic = BasicSetting::first();
        $basic->referral_commission_status = $request->referral_commission_status == 'on' ? 1 : 0;
        $basic->save();

        ReferralLevel::query()->delete();

        foreach ($request->input('commission') as $key => $level) {
            $key = ++$key;
            ReferralLevel::create([
                'level'      => 'level_' . $key,
                'commission' => $level,
            ]);
        }

        session()->flash('message', 'Referral Setting Updated');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    public function manageEmailTemplate()
    {
        $data['page_title'] = "Manage Email Template";
        $data['email'] = EmailSetting::first();

        return view('basic.email-template', $data);
    }

    /**
     * @param Request $request
     */
    public function updateEmailTemplate(Request $request)
    {
        $this->validate($request, [
            'email_body' => 'required',
        ]);
        $basic = EmailSetting::first();
        $basic->email_body = $request->email_body;
        $basic->save();
        session()->flash('message', 'Email Setting Updated.');
        Session::flash('type', 'success');

        return redirect()->back();
    }

    public function getEmailSetting()
    {
        $data['page_title'] = "Email Setting";
        $data['email'] = EmailSetting::first();

        return view('basic.email-setting', $data);
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function putEmailSetting(Request $request, $id)
    {
        $email = EmailSetting::findOrFail($id);
        $this->validate($request, [
            'email'    => 'required|email',
            'title'    => 'required',
            'driver'   => 'required',
            'host'     => 'required_if:driver,smtp',
            'port'     => 'required_if:driver,smtp|numeric',
            'username' => 'required_if:driver,smtp',
            'pass'     => 'required_if:driver,smtp',
        ]);
        $in = $request->except(['_method', '_token', 'pass']);
        $in['password'] = $request->pass;
        $in['encryption'] = empty(!$request->encryption) ? $request->encryption : null;
        $in['smtp_status'] = 0;

        $url = $request->host;
        $username = $request->username;
        $password = $request->pass;
        $port = $request->port;
        $enc = $in['encryption'];

        if ($request->driver === 'smtp') {
            $this->updateEnv([
                'MAIL_MAILER'       => "smtp",
                'MAIL_HOST'         => $url,
                'MAIL_PORT'         => $port,
                'MAIL_USERNAME'     => $username,
                'MAIL_PASSWORD'     => $password,
                'MAIL_ENCRYPTION'   => $enc,
                'MAIL_FROM_ADDRESS' => $request->email,
                'MAIL_FROM_NAME'    => $request->title,
            ]);

            try {
                Mail::to('softwarezon.me@gmail.com')->send(new VerifySMTP());
                $in['smtp_status'] = 1;
                $email->smtp_status = 1;
                $email->save();
                session()->flash('message', 'Email Setting Updated Successfully.');
                session()->flash('type', 'success');
            } catch (\Exception $e) {
                $in['smtp_status'] = 0;
                $email->smtp_status = 0;
                $email->save();
                session()->flash('message', 'Oops. SMTP credential are incorrect');
                session()->flash('type', 'warning');
            }
        } else {
            $this->updateEnv(['MAIL_MAILER' => "mail"]);
            session()->flash('message', 'Email Setting Updated Successfully.');
            session()->flash('type', 'success');
        }

        $email->update($in);

        return redirect()->back();
    }

    public function getDatabaseBackup()
    {
        $data['page_title'] = "Database Backup";
        $data['backup'] = DatabaseBackup::latest()->paginate(20);

        return view('basic.database-backup', $data);
    }

    /**
     * @return mixed
     */
    public function submitDatabaseBackup()
    {
        \session()->flash('message', 'Database Backup Created Successfully.');
        \session()->flash('type', 'success');

        return redirect()->route('database-backup');
    }

    /**
     * @param $id
     */
    public function downloadDatabaseBackup($id)
    {
        $db = DatabaseBackup::findOrFail($id);
        $this->DatabaseDownload($db->name);
        exit();
    }

    public function googleRecaptcha()
    {
        $data['page_title'] = 'Google Recaptcha';

        return view('basic.google-recaptcha', $data);
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
        $basic->captcha_status = $request->captcha_status == 'on' ? true : false;
        $basic->captcha_secret = $request->captcha_secret;
        $basic->captcha_site = $request->captcha_site;
        $basic->save();

        $this->updateEnv([
            'CAPTCHA_STATUS'  => $request->captcha_status == 'on' ? true : false,
            'CAPTCHA_SECRET'  => $basic->captcha_secret,
            'CAPTCHA_SITEKEY' => $basic->captcha_site,
        ]);

        session()->flash('message', 'Captcha Updated Successfully.');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    public function getGoogleAnalytic()
    {
        $data['page_title'] = "Google Analytic scripts";
        $data['heading'] = "Google Analytic";
        $data['filed'] = 'google_analytic';
        $data['nicEdit'] = 0;

        return view('basic.common-form', $data);
    }

    /**
     * @param Request $request
     */
    public function updateGoogleAnalytic(Request $request)
    {
        $basic = BasicSetting::first();
        $in = $request->except('_method', '_token');
        $basic->fill($in)->save();
        session()->flash('message', 'Google Analytic Updated.');
        Session::flash('type', 'success');

        return redirect()->back();
    }

    public function getLiveChat()
    {
        $data['page_title'] = "Live Chat scripts";
        $data['heading'] = "live Chat";
        $data['filed'] = 'chat';
        $data['nicEdit'] = 0;

        return view('basic.common-form', $data);
    }

    /**
     * @param Request $request
     */
    public function updateLiveChat(Request $request)
    {
        $basic = BasicSetting::first();
        $in = $request->except('_method', '_token');
        $basic->fill($in)->save();
        session()->flash('message', 'Chat Scripts Updated.');
        Session::flash('type', 'success');

        return redirect()->back();
    }

    public function smsSetting()
    {
        $data['page_title'] = "Manage SMS Setting";

        return view('basic.sms-setting', $data);
    }

    /**
     * @param Request $request
     */
    public function updateSmsSetting(Request $request)
    {
        $basic = BasicSetting::first();
        $basic->smsapi = $request->smsapi;
        $basic->save();
        session()->flash('message', 'SMS API Successfully Updated.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    public function smsTemplate()
    {
        $data['page_title'] = 'SMS Template';

        return view('basic.sms-template', $data);
    }

    /**
     * @param Request $request
     */
    public function submitSmsTemplate(Request $request)
    {
        $basic = BasicSetting::first();
        $request->validate([
            'sms_tem' => 'required',
        ]);
        $basic->sms_tem = $request->sms_tem;
        $basic->save();
        session()->flash('message', 'SMS Template Updated.');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    public function telegramConfig()
    {
        $data['page_title'] = 'Telegram Config';
        $driver = TelegramDriver::first();

        if ($driver == null) {
            $basic = BasicSetting::first();
            $driver['url'] = $basic->telegram_url ?? null;
            $driver['token'] = $basic->telegram_token ?? null;
            TelegramDriver::create($driver);
        }
        $data['driver'] = $driver;

        return view('basic.telegram-config', $data);
    }

    /**
     * @param Request $request
     */
    public function updateTelegramConfig(Request $request)
    {
        $request->validate([
            'telegram_token' => 'required',
            'telegram_url'   => 'required|url',
        ]);

        $basic = BasicSetting::first();

        $botToken = $request->telegram_token;
        $web = 'https://api.telegram.org/bot' . $botToken . "/getUpdates";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $web);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        $updateArray = json_decode($output, true);
        curl_close($ch);

        if ($updateArray['ok']) {
            $basic->telegram_token = $request->telegram_token;
            $basic->telegram_url = $request->telegram_url;
            $basic->save();
            session()->flash('message', 'Telegram Updated Successful.');
            session()->flash('type', 'success');

            return redirect()->back();
        } else {
            session()->flash('message', 'Telegram Bot have error.');
            session()->flash('type', 'warning');

            return redirect()->back();
        }

    }

    public function setCronJob()
    {
        $data['page_title'] = 'Cron Job URL';

        return view('basic.cron-job', $data);
    }
}
