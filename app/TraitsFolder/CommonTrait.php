<?php

namespace App\TraitsFolder;

use App\Models\User;
use App\Models\PaymentLog;
use App\Models\SmsGateway;
use App\Models\EmailDriver;
use Illuminate\Support\Str;
use App\Models\BasicSetting;
use App\Models\EmailSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Settings\SMS\SMSGlobalController;
use App\Http\Controllers\Settings\Email\EmailGlobalController;

trait CommonTrait
{

    /**
     * @param $userId
     * @param $logId
     */
    public static function manualPaymentEmail($userId, $logId)
    {
        $user = User::find($userId);
        $log = PaymentLog::find($logId);
        $driver = EmailDriver::whereStatus(true)->inRandomOrder()->first();
        $email = EmailSetting::first();
        $method = $log->paymentMethod->name;
        $amount = $log->amount . ' ' . $driver->data['sender_name'];

        $payload['email'] = $user->email;
        $payload['name'] = $user->name;
        $payload['subject'] = "Payment Request Receive - " . $driver->data['sender_name'];

        $text = "<b>We received your payment request. Our finance department will check it as soon as possible.</b><br><br>";
        $text .= "<b>Selected Method : $method</b><br>";
        $text .= "<b>Total Amount : $amount</b>";
        $body = $email->email_body;
        $body = str_replace("{{name}}", $user->name, $body);
        $body = str_replace("{{message}}", $text, $body);
        $body = str_replace("{{site_title}}", $driver->data['sender_name'], $body);
        $payload['view'] = 'emails.email';
        $payload['viewData'] = [
            'name' => $user->name,
            'body' => $body,
        ];

        (new EmailGlobalController($driver))->send($payload);
    }

    /**
     * @param $id
     */
    public function verificationSend($id)
    {
        $user = User::findOrFail($id);
        $basic = EmailSetting::first();
        $driver = EmailDriver::whereStatus(true)->inRandomOrder()->first();

        $payload['email'] = $user->email;
        $payload['name'] = $user->name;
        $payload['subject'] = "Email Verification - " . $driver->data['sender_name'];

        $text = '<h3>Please verify your email.</h3><h3>Your verification code is : ' . $user->email_code . '</h3>';
        $body = $basic->email_body;
        $body = str_replace("{{name}}", $user->name, $body);
        $body = str_replace("{{message}}", $text, $body);
        $body = str_replace("{{site_title}}", $basic->title, $body);

        $payload['view'] = 'emails.email';
        $payload['viewData'] = [
            'name' => $user->name,
            'body' => $body,
        ];
        (new EmailGlobalController($driver))->send($payload);

    }

    /**
     * @param $userID
     */
    public function phoneVerification($userID)
    {

        $driver = SmsGateway::whereStatus(true)->inRandomOrder()->first();
        $user = User::findOrFail($userID);
        $message = "Phone Verification Code: " . $user->phone_code;
        $phone = $user->country_code . $user->phone;
        (new SMSGlobalController($driver))->send($phone, $message);
    }

    /**
     * @param $email
     * @param $name
     * @param $subject
     * @param $text
     * @param $phone
     */
    public function sendContact($email, $name, $subject, $text, $phone)
    {
        $setting = EmailSetting::first();
        $basic = BasicSetting::first();

        $email = Purify::clean($email);
        $name = Purify::clean($name);
        $subject = Purify::clean($subject);
        $text = Purify::clean($text);
        $phone = Purify::clean($phone);

        $site_title = config('app.name');
        $body = $setting->email_body;
        $body = str_replace("Hi", 'Hi. I\'m', $body);
        $body = str_replace("{{name}}", $name . " - " . $phone, $body);
        $body = str_replace("{{message}}", $text, $body);
        $body = str_replace("{{site_title}}", $site_title, $body);

        $driver = EmailDriver::whereStatus(true)->inRandomOrder()->first();
        $payload['name'] = $basic->title;
        $payload['email'] = $basic->email;
        $payload['subject'] = 'Contact Message- ' . $subject;
        $payload['view'] = 'emails.email';
        $payload['viewData'] = [
            'name' => 'Admin',
            'body' => $body,
        ];
        (new EmailGlobalController($driver))->send($payload);
    }

    /**
     * @param $email
     * @param $name
     * @param $route
     */
    public function userPasswordReset($email, $name, $route)
    {
        $reset = DB::table('password_resets')->whereEmail($email)->count();
        $token = Str::random(40);
        $bToken = bcrypt($token);
        $url = route($route, $token);
        if ($reset == 0) {
            DB::table('password_resets')->insert(
                ['email' => $email, 'token' => $bToken]
            );
        } else {
            DB::table('password_resets')->where('email', $email)->update(['email' => $email, 'token' => $bToken]);
        }
        $driver = EmailDriver::whereStatus(true)->inRandomOrder()->first();
        $payload['email'] = $email;
        $payload['name'] = $name;
        $payload['subject'] = 'Password Reset Request';
        $payload['view'] = 'emails.reset-email';
        $payload['viewData'] = [
            'name'   => $name,
            'link'   => $url,
            'footer' => config('app.name'),
        ];
        (new EmailGlobalController($driver))->send($payload);

    }

    /**
     * @param $rating
     */
    public static function getRating($rating)
    {
        if ($rating == 0) {
            return '<i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i>';
        } elseif ($rating == 1) {
            return '<i class="fa fa-star star-color"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i>';
        } elseif ($rating == 2) {
            return '<i class="fa fa-star star-color"></i> <i class="fa fa-star star-color"></i> <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i>';
        } elseif ($rating == 3) {
            return '<i class="fa fa-star star-color"></i> <i class="fa fa-star star-color"></i> <i class="fa fa-star star-color"></i>
                                            <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i>';
        } elseif ($rating == 4) {
            return '<i class="fa fa-star star-color"></i> <i class="fa fa-star star-color"></i> <i class="fa fa-star star-color"></i>
                                            <i class="fa fa-star star-color"></i> <i class="fa fa-star-o"></i>';
        } else {
            return '<i class="fa fa-star star-color"></i> <i class="fa fa-star star-color"></i> <i class="fa fa-star star-color"></i>
                                            <i class="fa fa-star star-color"></i> <i class="fa fa-star star-color"></i>';
        }
    }

    /**
     * @param array $data
     * @return null
     */
    public function updateEnv($data = [])
    {
        if (!count($data)) {
            return;
        }
        $path = app()->environmentFilePath();
        $env = file_get_contents($path);
        $env = preg_split('/(\r\n|\r|\n)/', $env);

        foreach ((array) $data as $key => $value) {
            if (preg_match('/\s/', $value)) {
                $value = '"' . $value . '"';
            }
            foreach ($env as $env_key => $env_value) {
                $entry = explode("=", $env_value, 2);
                if ($entry[0] == $key) {
                    if (is_bool($value)) {
                        $env[$env_key] = $value ? $key . "=true" : $key . "=false";
                    } else {
                        $env[$env_key] = $key . "=" . $value;
                    }
                } else {
                    $env[$env_key] = $env_value;
                }
            }
        }
        $env = implode("\n", $env);
        file_put_contents($path, $env);

        Artisan::call('config:clear');
        Artisan::call('config:cache');
    }
}
