<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Http\Request;

use SmartBots\Http\Requests;
use Illuminate\Cache\RateLimiter;
use Illuminate\Auth\Events\Lockout;

use SmartBots\{User, TwilioNumber};

use Validator;

use Is;

use Cache;

use Exception;

use SmartBots\Events\VerifyServerSentEvents;

/**
 * Uses lot of ValidatesRequests trait
 */

class UserController extends Controller
{
    /**
     * on/off verify phone number feature
     * @var boolean
     */
    public $verify_phone_number = false;

    /**
     * Name (alias) of the route to redirect after user logged in
     * @var string
     */
    public $redirectAfterLogin = 'h::index';

    /**
     * Name (alias) of the route to redirect after user logged out
     * @var string
     */
    public $redirectAfterLogout = 'landing::index';

    public $guard = null;

    /**
     * Maximun login attempts for user
     * @var integer
     */
    public $maxLoginAttempts = 5;

    /**
     * If user reach maximun login attemps, lock them for second
     * @var integer
     */
    public $lockoutTime = 60; // second

    /**
     * Time for user to verify account
     * @var float
     */
    public $verify_ttl = 1.5; // minutes

    /**
     * Show up login page
     * @return Illuminate\Contracts\View\View
     */
    public function getLogin() {
        return view('account.login');
    }

    /**
     * Check if user login by username or email
     * @param  Request $request
     * @return boolean
     */
    public function loginWith(Request $request)
    {
        return Is::email()->validate($request->username) ? 'email' : 'username';
    }

    /**
     * Handle a request to login
     * @param  Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function postLogin(Request $request) {

        $rules = [
            'username' => 'required|between:6,255',
            'password' => 'required|between:6,255',
        ];

        $messenges = [];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $isUsingThrottles = property_exists($this, 'maxLoginAttempts') ? true : false;

        $lockedOut = $this->hasTooManyLoginAttempts($request);

        if ($isUsingThrottles && $lockedOut) {

            event(new Lockout($request));

            $seconds = $this->secondsRemainingOnLockout($request);

            $error = ['global' => trans('login.throttle',['second' => $seconds])];

            return response()->json($error);
        }

        $credentials = [
            $this->loginWith($request) => $request->username,
            'password' => $request->password
        ];

        if (auth()->guard($this->guard)->attempt($credentials, $request->has('remember'))) {

            if ($isUsingThrottles) {
                $this->clearLoginAttempts($request);
            }

            $error = ['success' => true, 'href' => route($this->redirectAfterLogin)];

            return response()->json($error);
        }

        if ($isUsingThrottles && !$lockedOut) {
            $this->incrementLoginAttempts($request);
        }

        $error = ['global' => trans('login.failed')];

        return response()->json($error);
    }

    /**
     * Show up register page
     * @return Illuminate\Contracts\View\View
     */
    public function getRegister() {
        return view('account.register');
    }

    /**
     * Handle a request to  sign up new account
     * @param  Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function postRegister(Request $request) {

        $rules = [
            'agree_with_terms'      => 'required',
            'username'              => 'required|between:6,255|unique:users',
            'name'                  => 'required|between:6,255',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|between:6,255|confirmed',
            'password_confirmation' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules, ['agree_with_terms.required' => trans('register.terms_disagreement')]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $newUser = new User;
        $newUser->username = $request->username;
        $newUser->name     = $request->name;
        $newUser->email    = $request->email;
        $newUser->password = bcrypt($request->password);

        if (!$this->verify_phone_number) {
            $newUser->verified = true;
        }

        $newUser->save();

        $error = ['success' => true];
        return response()->json($error);
    }

    /**
     * Show up forgot password page
     * @return Illuminate\Contracts\View\View
     */
    public function getForgot() {
        return view('account.forgot');
    }

    /**
     * Handle a request to retrieval password
     * @param  Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function postForgot(Request $request) {
        //
    }

    /**
     * Show up change password form
     * @return Illuminate\Contracts\View\View
     */
    public function getChangePass() {
        return view('account.change-pass');
    }

    /**
     * Handle a request to change password
     * @param  Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function postChangePass(Request $request) {
        $rules = [
            'currentpass'           => 'required|currentpassword',
            'password'              => 'required|between:6,255|confirmed',
            'password_confirmation' => 'required'
        ];

        $messenges = [
            'currentpass.currentpassword' => 'Your current password is incorrect'
        ];

        $validator = Validator::make($request->all(), $rules, $messenges);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $newUser = auth()->user();
        $newUser->password = bcrypt($request->password);
        $newUser->save();

        return response()->json(['success' => 'Saved successfully']);
    }

    /**
     * Logout of account
     * @return Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->guard($this->guard)->logout();
        session()->flush();
        return response()->json(['href' => route($this->redirectAfterLogout)]);
    }

    /**
     * Show up account edit form
     * @return Illuminate\Contracts\View\View
     */
    public function edit() {
        $user = auth()->user();
        return view('account.edit')->withUser($user);
    }

    /**
     * Handle a request to edit account info
     * @param  Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function update(Request $request) {

        $rules = [
            'name'  => 'required|between:6,255',
            'email' => 'required|email|unique:users,id,'.auth()->user()->id,
            'phone' => 'required|numeric|unique:users,id,'.auth()->user()->id
        ];

        $validator = Validator::make($request->all(), $rules, ['agree_with_terms.required' => trans('register.terms_disagreement')]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = auth()->user();
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if (!empty($request->image_values)) {
            if (!empty($request->image_values)) {
                $user->avatar = upload_base64_image(json_decode($request->image_values)->data);
            }
        }

        $user->save();

        $error = ['success' => 'User profile saved successfully'];
        return response()->json($error);
    }

    /**
     * Search for user
     * @param  string $query
     * @return Illuminate\Http\JsonResponse
     */
    public function search(string $query) {
        $users = User::select(['username','avatar','name'])->where('username','LIKE','%'.$query.'%')->orWhere('id','LIKE','%'.$query.'%')->get()->toArray();

        for ($i=0;$i<count($users);$i++) {
            $users[$i]['avatar'] = asset($users[$i]['avatar']);
        }

        return response()->json($users);
    }

    /**
     * Show up verify page
     * @return Illuminate\Contracts\View\View
     */
    public function getVerify() {
        return view('account.verify')->withPhone(auth()->user()->phone);
    }

    /**
     * Handle a request to verify account
     * @param  Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function postVerify(Request $request) {

        $rules = ['phone' => 'required|phone:AUTO|unique:users,id,'.auth()->user()->id];

        $validator = Validator::make($request->only('phone'), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        if (!Cache::tags($request->phone)->has('verifying')) {

            $number_to_dial = TwilioNumber::getRandomNumber();
            Cache::tags($request->phone)->put('verifying', $number_to_dial, $this->verify_ttl);
        } else {
            $number_to_dial = Cache::tags($request->phone)->get('verifying');
        }

        auth()->user()->phone = $request->phone;
        auth()->user()->save();

        $error = ['success' => true, 'number_to_dial' => $number_to_dial];
        return response()->json($error);

    }

    /**
     * Handle a Twilio request when it has a call
     * @param  Request $request
     * @return null
     */
    public function handleTwilioVoiceStatusCallback(Request $request) {

        $data      = $request->instance()->request->all();
        $signature = $request->header('X-Twilio-Signature');
        $url       = $request->fullUrl();

        ksort($data);

        foreach($data as $key => $value) {
           $url .= $key.$value;
        }

        $sha1 = hash_hmac('sha1', $url, $this->getTwilioToken(), true);
        $generated_sig = base64_encode($sha1);

        if ($generated_sig !== $signature) {
            throw new Exception("NOT_AUTHORIZED", 401);
        }

        if (Cache::tags($data['From'])->get('verifying') == $data['To']) {

            $sse = new VerifyServerSentEvents($data['From']);
            $sse->sendVerified();

            auth()->user()->verified = true;
            auth()->user()->save();
        }
    }

    /**
     * Send verify status to user via Eventsource (Server-Sent Events)
     * @return [type] [description]
     */
    public function getVerifyStatus() {

        $number = auth()->user()->phone;

        $sse = new VerifyServerSentEvents($number);

        if (!Cache::tags($number)->has('verifying')) {
            $sse->sendExpired();
        }

        $sse->output();
    }
    //---------------------------------------------------------------------------------------------------------------------

    /**
     * Create a throtte-key from user's ip
     * @param  Request $request
     * @return string
     */
    public function getThrottleKey(Request $request)
    {
        return $request->ip();
        // return mb_strtolower($request->username).'|'.$request->ip();
    }

    /**
     * Check if user has too many login attempts
     * @param  Request $request
     * @return boolean
     */
    public function hasTooManyLoginAttempts(Request $request)
    {
        return app(RateLimiter::class)->tooManyAttempts(
            $this->getThrottleKey($request),
            $this->maxLoginAttempts, $this->lockoutTime / 60
        );
    }

    /**
     * Increate user's login attempts
     * @param  Request $request
     * @return void
     */
    public function incrementLoginAttempts(Request $request)
    {
        app(RateLimiter::class)->hit(
            $this->getThrottleKey($request)
        );
    }

    /**
     * Get retries attempts left
     * @param  Request $request
     * @return int
     */
    public function retriesLeft(Request $request)
    {
        return app(RateLimiter::class)->retriesLeft(
            $this->getThrottleKey($request),
            $this->maxLoginAttempts
        );
    }

    /**
     * Availavle to login in (second)
     * @param  Request $request
     * @return int
     */
    public function secondsRemainingOnLockout(Request $request)
    {
        return app(RateLimiter::class)->availableIn(
            $this->getThrottleKey($request)
        );
    }

    /**
     * Clear loggin attempts when user loged in succesfully
     * @param  Request $request
     * @return void
     */
    public function clearLoginAttempts(Request $request)
    {
        app(RateLimiter::class)->clear(
            $this->getThrottleKey($request)
        );
    }

}
