<?php

namespace App\Http\Controllers;

use App\Models\SmsGateway;
use App\Models\UserSignal;
use App\Models\EmailDriver;
use App\Models\TelegramDriver;
use App\Models\WhatsappDriver;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Settings\SMS\SMSGlobalController;
use App\Http\Controllers\Settings\Email\EmailGlobalController;
use App\Http\Controllers\Settings\Whatsapp\WhatsappGlobalController;

class CronController extends Controller
{
    /**
     * @return mixed
     */
    public function telegramCron()
    {
        $driver = TelegramDriver::first();
        $signals = UserSignal::with([
            'user',
            'signal',
            'signal.asset',
            'signal.symbol',
            'signal.type',
            'signal.frame',
            'signal.status',
        ])->where('telegram_alert', 1)->where('telegram_attempt', '<=', 5)->take(10)->get();

        $web = "https://api.telegram.org/bot{$driver->token}";

        foreach ($signals as $sig) {
            $signal = $sig->signal;
            $user = $sig->user;
            $text = $this->prepareMessage($sig);
            $textT = urlencode($text);

            if ($signal->image != 'default.jpg') {
                $path = asset("assets/images/signal/$signal->image");
                $url = ($web . "/sendPhoto?chat_id=" . $user->telegram_id . "&photo=" . $path . "&caption=$textT");
            } else {
                $url = ($web . "/sendMessage?chat_id=" . $user->telegram_id . "&text=" . $textT . "&parse_mode=html");
            }
            $response = Http::get($url);
            if ($response->successful()) {
                $sig->telegram_alert = 0;
            } else {
                $sig->telegram_attempt = ++$sig->telegram_attempt;
            }
            $sig->save();
        }
    }

    public function whatsappCron()
    {
        $driver = WhatsappDriver::whereStatus(true)->inRandomOrder()->first();
        $signals = UserSignal::with([
            'user',
            'signal',
            'signal.asset',
            'signal.symbol',
            'signal.type',
            'signal.frame',
            'signal.status',
        ])->where('whatsapp_alert', 1)->where('whatsapp_attempt', '<=', 5)->take(10)->get();

        foreach ($signals as $sig) {
            $user = $sig->user;
            $text = $this->prepareMessage($sig);

            $payload['number'] = $user->whatsapp_id;
            $payload['message'] = $text;
            $action = (new WhatsappGlobalController($driver))->send($payload);

            if ($action['success']) {
                $sig->whatsapp_alert = 0;
            } else {
                $sig->whatsapp_attempt = ++$sig->whatsapp_attempt;
            }
            $sig->save();
        }
    }

    /**
     * @return mixed
     */
    public function smsCron()
    {
        $driver = SmsGateway::whereStatus(true)->inRandomOrder()->first();
        $signals = UserSignal::with([
            'user',
            'signal',
            'signal.asset',
            'signal.symbol',
            'signal.type',
            'signal.frame',
            'signal.status',
        ])->where('sms_alert', 1)->where('sms_attempt', '<=', 5)->take(10)->get();

        foreach ($signals as $sig) {
            $user = $sig->user;
            $text = $this->prepareMessage($sig);

            $number = str_replace('+', '', $user->country_code . $user->phone);
            $message = $text;

            $action = (new SMSGlobalController($driver))->send($number, $message);

            if ($action['success']) {
                $sig->sms_alert = 0;
            } else {
                $sig->sms_attempt = ++$sig->sms_attempt;
            }
            $sig->save();
        }
    }

    public function emailCron()
    {
        $driver = EmailDriver::whereStatus(true)->inRandomOrder()->first();
        $signals = UserSignal::with([
            'user',
            'signal',
            'signal.asset',
            'signal.symbol',
            'signal.type',
            'signal.frame',
            'signal.status',
        ])->where('email_alert', 1)->where('email_attempt', '<=', 5)->take(10)->get();

        foreach ($signals as $sig) {
            $signal = $sig->signal;
            $user = $sig->user;
            $text = $this->prepareMessage($sig, true);

            $payload['email'] = $user->email;
            $payload['name'] = $user->name;
            $payload['subject'] = '#' . custom($signal->id) . ' Signal Notification.';
            if ($signal->image != 'default.jpg') {
                $payload['attachment'] = asset("assets/images/signal/$signal->image");
            }

            $payload['view'] = 'emails.signal';

            $payload['viewData'] = [
                'name' => $user->name,
                'body' => $text,
            ];

            $action = (new EmailGlobalController($driver))->send($payload);

            if ($action['success']) {
                $sig->email_alert = 0;
            } else {
                $sig->email_attempt = ++$sig->email_attempt;
            }
            $sig->save();
        }
    }

    /**
     * @param $sig
     */
    public function prepareMessage($sig, $html = false)
    {
        $signal = $sig->signal;
        $user = $sig->user;
        $symbol = $html ? "<br>" : "\n";

        $text = "#" . custom($signal->id) . "$symbol";
        $text .= "TITLE : $signal->title" . $symbol;
        $text .= "ASSETS: " . $signal->asset->name . "$symbol";
        $text .= "SYMBOL: " . $signal->symbol->name . "$symbol";
        $text .= "TYPE:  " . $signal->type->name . "$symbol";
        $text .= "OPEN: " . $signal->entry . "$symbol";
        $text .= "TP: " . $signal->profit . "$symbol";
        if ($signal->profit_two) {
            $text .= "TP 2: " . $signal->profit_two . "$symbol";
        }if ($signal->profit_three) {
            $text .= "TP 3: " . $signal->profit_three . "$symbol";
        }
        $text .= "STOP : " . $signal->loss . "$symbol";
        $text .= "TF:  " . $signal->frame->name . "$symbol";
        $text .= "STATUS : " . $signal->status->name . "$symbol";

        return $text;
    }
}
