<?php

namespace App\Http\Controllers;

use Http;
use Carbon\Carbon;
use App\Models\Menu;
use App\Models\Plan;
use App\Models\Post;
use App\Models\User;
use App\Models\Member;
use App\Models\Signal;
use App\Models\Slider;
use App\Models\Social;
use App\Models\Partner;
use App\Models\Category;
use App\Models\Subscribe;
use App\Models\Speciality;
use App\Models\Testimonial;
use App\Models\EmailSetting;
use Illuminate\Http\Request;
use App\TraitsFolder\CommonTrait;
use App\Models\SubscribeMessageCron;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    use CommonTrait;
    public function getIndex()
    {
        $data['page_title'] = "Home Page";
        $data['slider'] = Slider::all();
        $data['category'] = Category::whereStatus(1)->get();
        $data['testimonial'] = Testimonial::all();
        $data['member'] = Member::all();
        $data['speciality'] = Speciality::all();
        $data['plan'] = Plan::whereStatus(1)->get();
        $data['total_user'] = User::count();
        $data['total_blog'] = Post::count();
        $data['total_signal'] = Signal::count();
        $data['social'] = Social::all();
        $data['partner'] = Partner::all();
        $data['blog'] = Post::with('category')->latest()->take(6)->get();
        $data['menus'] = Menu::all();
        $data['footer_category'] = Category::whereStatus(1)->take(7)->get();
        $data['footer_blog'] = Post::orderBy('views', 'desc')->take(7)->get();

        $data['pips_sum'] = Signal::sum('pips');

        $data['signals'] = Signal::whereHome(1)
            ->with([
                'symbol:id,name',
                'type:id,name',
                'asset:id,name',
                'frame:id,name',
                'status:id,name',
            ])
            ->orderByDesc('id')
            ->take(8)
            ->get();

        return view('home.home', $data);
    }

    /**
     * @param $id
     * @param $slug
     */
    public function getMenu($id, $slug)
    {
        $data['men'] = Menu::whereId($id)->first();
        $data['page_title'] = $data['men']->name;
        $data['menus'] = Menu::all();
        $data['social'] = Social::all();
        $data['category'] = Category::whereStatus(1)->get();
        $data['footer_category'] = Category::whereStatus(1)->take(7)->get();
        $data['footer_blog'] = Post::orderBy('views', 'desc')->take(7)->get();
        return view('home.menus', $data);
    }

    public function getAbout()
    {
        $data['page_title'] = 'About Us';
        $data['menus'] = Menu::all();
        $data['social'] = Social::all();
        $data['category'] = Category::whereStatus(1)->get();
        $data['footer_category'] = Category::whereStatus(1)->take(7)->get();
        $data['team'] = Member::all();
        $data['plan'] = Plan::whereStatus(1)->get();
        $data['total_user'] = User::all()->count();
        $data['total_category'] = Category::all()->count();
        $data['total_blog'] = Post::all()->count();
        $data['total_signal'] = Signal::all()->count();
        $data['testimonial'] = Testimonial::all();
        $data['footer_blog'] = Post::orderBy('views', 'desc')->take(7)->get();
        return view('home.about-us', $data);
    }

    public function getContact()
    {
        $data['page_title'] = 'Contact Us';
        $data['menus'] = Menu::all();
        $data['social'] = Social::all();
        $data['category'] = Category::whereStatus(1)->get();
        $data['footer_category'] = Category::whereStatus(1)->take(7)->get();
        $data['footer_blog'] = Post::orderBy('views', 'desc')->take(7)->get();
        return view('home.contact-us', $data);
    }

    /**
     * @param Request $request
     */
    public function submitContact(Request $request)
    {
        $request->validate([
            'name'    => 'required',
            'email'   => 'required',
            'subject' => 'required',
            'phone'   => 'required',
            'message' => 'required',
        ]);
        $this->sendContact($request->email, $request->name, $request->subject, $request->message, $request->phone);
        session()->flash('message', 'Contact Message Successfully Send.');
        session()->flash('type', 'success');
        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    public function submitSubscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:subscribes,email',
        ]);
        $in = $request->except('_method', '_token');
        Subscribe::create($in);
        session()->flash('message', 'Subscribe Completed.');
        session()->flash('type', 'success');
        return redirect()->back();
    }

    public function getBlog()
    {
        $data['page_title'] = 'Blog Page';
        $data['blog'] = Post::with('category')->latest()->paginate(3);
        $data['category'] = Category::whereStatus(1)->withCount('posts')->get();
        $data['social'] = Social::all();
        $data['popular'] = Post::orderBy('views', 'desc')->take(15)->get();
        $data['menus'] = Menu::all();
        $data['footer_category'] = Category::whereStatus(1)->take(7)->get();
        $data['footer_blog'] = Post::orderBy('views', 'desc')->take(7)->get();
        return view('home.blog', $data);
    }

    /**
     * @param $slug
     */
    public function detailsBlog($slug)
    {
        $data['page_title'] = 'Blog Details';
        $data['blog'] = Post::whereSlug($slug)->first();
        $data['blog']->views = $data['blog']->views + 1;
        $data['category'] = Category::withCount('posts')->whereStatus(1)->get();
        $data['social'] = Social::all();
        $data['popular'] = Post::orderBy('views', 'desc')->take(10)->get();
        $data['blog']->save();
        $data['menus'] = Menu::all();
        $data['footer_category'] = Category::whereStatus(1)->take(7)->get();
        $data['footer_blog'] = Post::orderBy('views', 'desc')->take(7)->get();
        $data['meta'] = 2;
        return view('home.blog-details', $data);
    }

    /**
     * @param $slug
     */
    public function categoryBlog($slug)
    {
        $category = Category::whereSlug($slug)->first();
        $data['page_title'] = $category->name . ' - Blog';
        $data['blog'] = Post::with('category')->whereCategory_id($category->id)->latest()->paginate(3);
        $data['category'] = Category::whereStatus(1)->get();
        $data['social'] = Social::all();
        $data['popular'] = Post::orderBy('views', 'desc')->take(15)->get();
        $data['menus'] = Menu::all();
        $data['footer_category'] = Category::whereStatus(1)->take(7)->get();
        $data['footer_blog'] = Post::orderBy('views', 'desc')->take(7)->get();
        return view('home.blog', $data);
    }

    public function getTermCondition()
    {
        $data['page_title'] = "Term And Condition";
        $data['social'] = Social::all();
        $data['menus'] = Menu::all();
        $data['category'] = Category::whereStatus(1)->get();
        $data['footer_category'] = Category::whereStatus(1)->take(7)->get();
        $data['footer_blog'] = Post::orderBy('views', 'desc')->take(7)->get();
        return view('home.term-condition', $data);
    }

    public function getPrivacyPolicy()
    {
        $data['page_title'] = "Privacy And Policy";
        $data['social'] = Social::all();
        $data['menus'] = Menu::all();
        $data['category'] = Category::whereStatus(1)->get();
        $data['footer_category'] = Category::whereStatus(1)->take(7)->get();
        $data['footer_blog'] = Post::orderBy('views', 'desc')->take(7)->get();
        return view('home.privacy-policy', $data);
    }

    public function submitCronSubscribeMessage()
    {

        $lists = SubscribeMessageCron::take(10)->get();

        foreach ($lists as $list) {
            $email = EmailSetting::first();
            $subs = Subscribe::find($list->subscriber_id);
            $mail_val = [
                'email'   => $subs->email,
                'name'    => '',
                'g_email' => $email->email,
                'g_title' => $email->name,
                'subject' => $list->message->title,
            ];

            $body = $list->message->message;

            Mail::send('emails.email', ['body' => $body], function ($m) use ($mail_val) {
                $m->from($mail_val['g_email'], $mail_val['g_title']);
                $m->to($mail_val['email'], $mail_val['name'])->subject($mail_val['subject']);
            });

            $list->delete();

            echo "Subscriber Notification Send.<br>";

        }
    }

    public function submitCronJob()
    {
        $user = User::wherePlan_status(1)->where('expire_time', '!=', 1)->get();
        foreach ($user as $u) {
            if ($u->expire_time < Carbon::now()) {
                $u->plan_status = 0;
                $u->save();
            }
        }
    }

    public function submitCronJobSignal()
    {
        /* $telegramCron = route('cron-signal-telegram');
        $whatsappCron = route('cron-signal-whatsapp');
        $smsCron = route('cron-signal-sms');
        $emailCron = route('cron-signal-email'); */
        $cron = new CronController();
        $telegramResponse = $cron->telegramCron();
        $whatsappResponse = $cron->whatsappCron();
        $smsResponse = $cron->smsCron();
        $emailResponse = $cron->emailCron();
    }

}
