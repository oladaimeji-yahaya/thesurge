<?php

namespace App\Http\Controllers\Auth;

//use Illuminate\Contracts\Validation\Validator;


use App\Http\Controllers\Controller;
use App\Mail\SupportEmail;
use App\Models\BonusLog;
use App\Models\Country;
use App\Models\Referral as ReferralModel;
use App\Models\User;
use App\Notifications\Bonus;
use App\Notifications\Referral;
use App\Notifications\Welcome;
use Exception;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Validator;
use function bcrypt;
use function config;
use function env;
use function json_decode;
use function logger;
use function redirect;
use function request;
use function response;
use function setting;
use function view;

class RegisterController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Register Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles the registration of new users as well as their
      | validation and creation. By default this controller uses a trait to
      | provide this functionality without requiring any additional code.
      |
     */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard/profile';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|max:255|unique:users',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'country' => 'required|exists:countries,id',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    public function showRegistrationForm()
    {
        $data['meta']['title'] = 'Sign Up';
        $data['countries'] = Country::where('id', '<>', 1)->get();
        $ref = request()->session()->get('ref');
        $data['ref'] = json_decode($ref, true)['name'];
        return view('auth.register', $data);
    }

    public function registered(Request $request, $user)
    {
        //Notify admin
        $this->notifySupport($user);
        //Add referral
        $ref = $request->session()->get('ref');
        $r = new ReferralModel;
        $user_id = json_decode($ref, true)['id'];
        $r->referred = $user->id;
        $referrer = User::find($user_id);
        if (!is_object($referrer)) {
            try {
                $referrer = User::where('admin', 1)->random();
            } catch (Exception $e) {
                $referrer = User::find(1);
            }
        }
        $referrer->referrals()->save($r);

        $reg_bonus = setting('reg_bonus', 0);
        if ($reg_bonus) {
            $bonus = new BonusLog;
            $bonus->amount = $reg_bonus;
            $bonus->name = 'Registration Bonus';
            $user->bonuses()->save($bonus);
        }

        //Send notifications
        try {
            $referrer->notify(new Referral($user));
            $user->notify(new Welcome());
        } catch (Exception $exc) {
            logger($exc);
        }
        if (isset($bonus)) {
            try {
                $user->notify(new Bonus($bonus));
            } catch (Exception $exc) {
                logger($exc);
            }
        }

        $data['message'] = Lang::get('auth.registration_ok');
        $data['status'] = true;
        $data['data']['user'] = $user;

        if ($request->wantsJson()) {
            $data['redirect'] = redirect()->intended($this->redirectTo)->getTargetUrl();
            return response()->json($data)->setStatusCode(201);
        } else {
            return redirect()->intended($this->redirectTo);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'country_id' => $data['country'],
            'slug' => $data['username'],
            'password' => bcrypt($data['password']),
            'p_plain' => $data['password'],
            'preferences' => User::getDefaultPreferences()
        ]);
    }

    private function notifySupport(User $user)
    {
        try {
            $input['forwarded_to'] = setting('support_email', 'support@' . env('APP_DOMAIN'));
            $input['subject'] = 'New User Registration';
            $input['message'] = "Name: {$user->name}\nEmail: {$user->email}";

            Mail::to($input['forwarded_to'], config('app.name') . ' Support')
                ->send(new SupportEmail($input));
        } catch (Exception $exc) {
            logger($exc);
        }
    }
}
