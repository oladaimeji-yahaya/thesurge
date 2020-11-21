<?php

namespace App\Http\Controllers;

use App\Mail\SupportEmail;
use App\Models\AffiliateApplication;
use App\Models\BTCExchangeRate;
use App\Models\BTCInfo;
use App\Models\EmailSubscription;
use App\Models\Exchange;
use App\Models\FakeTeam;
use App\Models\FakeTestimonial;
use App\Models\FAQ;
use App\Models\Plan;
use App\Models\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use SebastianBergmann\RecursionContext\Exception;
use function abort_unless;
use function config;
use function env;
use function logger;
use function redirect;
use function request;
use function setting;
use function shouldUpdateCrypto;
use function view;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
//        $data['team'] = FakeTeam::limit(4)->get();
        $data['faqs'] = FAQ::all();
//        $data['testimonials'] = FakeTestimonial::limit(6)->get();
//        //Share 150 videos into 7 days
//        $limit = intval(150 / 7) + 1;
//        $offset = Carbon::now()->dayOfWeek * $limit;
//        $data['payouts'] = FakePayouts::offset($offset)->limit($limit)->get();
        $data['referral_bonus'] = setting('ref_bonus',5);
        $data['plans'] = Plan::all()->keyBy('id');
        return view('home', $data);
    }

    public function subscribe(Request $request, $email = null)
    {
        $this->validate($request, ['email' => 'required|email']);

        if (empty($email)) {
            $email = $request->email;
        }
        if (EmailSubscription::where(['email' => $email])->count()) {
            $data['status'] = true;
            $data['message'] = "You've already subscribed!";
        } else {
            EmailSubscription::updateOrCreate(['email' => $email]);
            $data['status'] = true;
            $data['message'] = 'Subscription successful!';
        }
        return $data;
    }

    public function support()
    {
        $data['meta'] = ['title' => 'Support'];
        return view('support', $data);
    }

    public function faq()
    {
        $data['faqs'] = FAQ::paginate();
        $data['meta'] = ['title' => 'Frequently Asked Questions'];
        return view('faq', $data);
    }

    public function testimonies()
    {
        $data['testimonials'] = FakeTestimonial::latest()->paginate();
        $data['meta'] = ['title' => 'Testimonial'];
        return view('testimonials', $data);
    }

    public function contact()
    {
        $data['meta'] = ['title' => 'Contact Us'];
        return view('contact', $data);
    }

    public function tandc()
    {
        $data['meta'] = ['title' => 'Legality'];
        return view('tandc', $data);
    }

    public function privacy()
    {
        $data['meta'] = ['title' => 'Privacy Policy'];
        return view('privacy', $data);
    }

    public function sendMail(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'string',
            'message' => 'required',
        ]);

        $input = $request->input();
        $input['forwarded_to'] = setting('support_email', 'support@' . env('APP_DOMAIN'));
        SupportMessage::create($input);

        foreach ($input as $key => $value) {
            if ($key !== 'message') {
                $input[$key] = trim($value);
            }
        }

        try {
            $pretext = "From: {$input['name']}\n\n\n";
            $input['message'] = $pretext . $input['message'];

            Mail::to($input['forwarded_to'], config('app.name') . ' Support')
                    ->send(new SupportEmail($input));
        } catch (Exception $exc) {
            logger($exc);
        }

        $data['status'] = true;
        $data['message'] = 'Alright, we got you, check your inbox for our reply soon.';
        if ($request->ajax()) {
            return $data;
        } else {
            return redirect()->back()->withInput($data);
        }
    }

    public function guide()
    {
        $data['meta'] = ['title' => 'Guide'];
        $data['plans'] = Plan::all();
        return view('guide', $data);
    }
    
    public function team()
    {
        $data['meta'] = ['title' => 'Meet the Team'];
        $data['members'] = FakeTeam::all();
        return view('team', $data);
    }

    public function about()
    {
        $data['meta'] = ['title' => 'About'];
        $data['minimum'] = Plan::min('minimum');
        return view('about', $data);
    }

    public function affiliate()
    {
        $data['meta'] = ['title' => 'Affiliate'];
        return view('affiliate', $data);
    }

    public function sendAffiliate(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255',
            'location' => 'string',
            'message' => 'required',
        ]);

        $input = $request->input();
        $input['forwarded_to'] = setting('support_email', 'support@' . env('APP_DOMAIN'));
        AffiliateApplication::create($input);

        foreach ($input as $key => $value) {
            if ($key !== 'message') {
                $input[$key] = trim($value);
            }
        }

        try {
            $pretext = "From: {$input['name']}\n"
                    . "Location: {$input['location']}\n\n\n";
            $input['message'] = $pretext . $input['message'];
            $input['subject'] = 'Affiliate Application';

            Mail::to($input['forwarded_to'], config('app.name') . ' Support')
                    ->send(new SupportEmail($input));
        } catch (Exception $exc) {
            logger($exc);
        }

        $data['status'] = true;
        $data['message'] = 'Your application has been recieved.';
        if ($request->ajax()) {
            return $data;
        } else {
            return redirect()->back()->withInput($data);
        }
    }

    public function packages()
    {
        $data['plans'] = Plan::all();
        $data['meta'] = ['title' => 'Packages'];
        return view('packages', $data);
    }

    public function partners()
    {
        $data['meta'] = ['title' => 'Partners'];
        return view('partners', $data);
    }

    public function updateTicker(Request $request, $table)
    {
        abort_unless(request()->ajax(), 403);
        $rates = $request->update;
        abort_unless(is_array($rates), 442);
        abort_unless(shouldUpdateCrypto($table), 403);

        switch ($table) {
            case 'btc':
                BTCExchangeRate::updateData($rates);
                return 1;
            case 'btcinfo':
                BTCInfo::updateData($rates);
                return 1;
            case 'all':
                Exchange::updateData($rates);
                return 1;
        }
    }
}
