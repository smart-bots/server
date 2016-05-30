<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Http\Request;

use SmartBots\Http\Requests;
use Illuminate\Cache\RateLimiter;
use Illuminate\Auth\Events\Lockout;

use SmartBots\User;

use Validator;

/**
 * Uses lot of ValidatesRequests trait
 */

class UserController extends Controller
{

	public $redirectAfterLogin = 'h::index'; // Route

    public $redirectAfterLogout = 'a::login';

	public $guard = null;

	public $maxLoginAttempts = 5;

	public $lockoutTime = 60;

    public function getLogin() {
    	return view('account.login');
    }

    public function loginWith(Request $request)
    {
    	return is_email($request->username) ? 'email' : 'username';
    }

    public function postLogin(Request $request) {

    	$rules = [
            'username' => 'required|between:6,255',
            'password' => 'required|between:6,255',
		];

        $messenges = [
        ];

        // foreach ($rules as $input => $rule) {
        //     $validator = Validator::make([$input => $request->$input], [$input => $rule]);
        //     if ($validator->fails()) {
        //         $error = [$input => $validator->errors()->first($input)];
        //         return response()->json($error);
        //     }
        // }

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
            // Success
            $error = ['success' => true, 'href' => route($this->redirectAfterLogin)];

            return response()->json($error);
        }

        if ($isUsingThrottles && !$lockedOut) {
            $this->incrementLoginAttempts($request);
        }

        $error = ['global' => trans('login.failed')];

        return response()->json($error);
    }

    public function getRegister() {
    	return view('account.register');
    }

    public function postRegister(Request $request) {

		$rules = [
			'agree_with_terms'      => 'required',
			'username'              => 'required|between:6,255|unique:users',
			'name'                  => 'required|between:6,255',
			'email'                 => 'required|email|unique:users',
			'phone'                 => 'required|numeric|unique:users',
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
		$newUser->phone    = $request->phone;
		$newUser->password = bcrypt($request->password);
		$newUser->save();

        $error = ['success' => true];
        return response()->json($error);
    }

    public function getForgot() {
    	return view('account.forgot');
    }

    public function postForgot(Request $request) {

    }

    public function logout() {
    	auth()->guard($this->guard)->logout();
        session()->flush();
        return response()->json(['href' => route($this->redirectAfterLogout)]);
    }

    public function edit() {
        $user = auth()->user();
        return view('account.edit')->withUser($user);
    }

    public function update(Request $request) {
        $rules = [
            'name'  => 'required|between:6,255',
            'email' => 'required|email|unique:users,id,'.auth()->user()->id,
            'phone' => 'required|numeric|unique:users,id,'.auth()->user()->id
        ];

        $this->validate($request,$rules);

        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if (!empty($request->image_values)) {
            $image_values  = json_decode($request->image_values);
            $image_name    = str_random(10).'.jpg';
            $image_base64  = explode(',', $image_values->data);
            file_put_contents(base_path().env('UPLOAD_IMAGES_FOLDER').'/'.$image_name, base64_decode($image_base64[1]));
            $user->avatar = env('UPLOAD_IMAGES_FOLDER').'/'.$image_name;
        }

        $user->save();

        return view('account.edit')->withUser($user)->withSuccess(true);
    }

    public function search($query) {
        $users = User::select(['username','avatar','name'])->where('username','LIKE','%'.$query.'%')->orWhere('id','LIKE','%'.$query.'%')->get()->toArray();
        for ($i=0;$i<count($users);$i++) {
            $users[$i]['avatar'] = asset($users[$i]['avatar']);
        }
        return response()->json($users);
    }

    //---------------------------------------------------------------------------------------------------------------------

    public function getThrottleKey(Request $request)
    {
        return $request->ip();
        // return mb_strtolower($request->username).'|'.$request->ip();
    }

    public function hasTooManyLoginAttempts(Request $request)
    {
        return app(RateLimiter::class)->tooManyAttempts(
            $this->getThrottleKey($request),
            $this->maxLoginAttempts, $this->lockoutTime / 60
        );
    }

    public function incrementLoginAttempts(Request $request)
    {
        app(RateLimiter::class)->hit(
            $this->getThrottleKey($request)
        );
    }

    public function retriesLeft(Request $request)
    {
        return app(RateLimiter::class)->retriesLeft(
            $this->getThrottleKey($request),
            $this->maxLoginAttempts
        );
    }

    public function secondsRemainingOnLockout(Request $request)
    {
        return app(RateLimiter::class)->availableIn(
            $this->getThrottleKey($request)
        );
    }

    public function clearLoginAttempts(Request $request)
    {
        app(RateLimiter::class)->clear(
            $this->getThrottleKey($request)
        );
    }

}
