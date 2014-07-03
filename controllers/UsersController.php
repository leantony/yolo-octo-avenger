<?php

class UsersController extends \BaseController {

	// just a csrf filter for security
	public function __construct()
	{
		$this->beforeFilter('csrf', [ 'on' => 'post' ]);
	}

	/**
	 * allow users to signup by displaying a signup form
	 * @return [type] [description]
	 */
	public function getSignup()
	{
		$this->layout->content = View::make('users.signup');
	}

	/**
	 * signup logic
	 * @return [type] [description]
	 */
	public function postSignup()
	{
		// call the validator and pass in the data and our rules
		$validator = Validator::make(Input::all(), User::$rules);
		if ($validator->passes()){
			// create an instance of a user
			$user = new User;
			$user->username = Input::get('username');
			$user->password = Hash::make(Input::get('password'));
			$user->save();

			// we save the user and redirect with a flash msg
			return Redirect::to('users/signin')->with('message', 'Thanks for signing up')
			->with('alertclass', 'alert-success');
		}
		else{
			// redirect on failure, passing in the errors to the form
			// then resend user input
			return Redirect::to('users/signup')
			->with('message', $this->errormsg)->with('alertclass','alert-danger')
			->withErrors($validator)->withInput();
		}
	}

	/**
	 * allow users to signin by displaying a signin form
	 * @return [type] [description]
	 */
	public function getSignin()
	{
		$this->layout->content = View::make('users.signin');
	}

	/**
	 * signin logic e.g authentication
	 * @return [type] [description]
	 */
	public function postSignin()
	{
		$username = Input::get('username');
		$password = Input::get('password');
		if (Auth::attempt(['username'=>$username, 'password'=>$password])){
			return Redirect::to('/')->with('message', 'You\'ve successfully signed in');
		} else {
			return Redirect::to('users/signin')
			->with('message', 'Invalid credentials. try signing in with valid credentials')
			->with('alertclass', 'alert-danger')
			->withInput();
		}
	}

	/**
	 * allow users to signout
	 * @return [type] [description]
	 */
	public function getSignout()
	{
		Auth::logout();
		return Redirect::to('/')
			->with('message', 'You\'ve successfully signed out')
			->with('alertclass', 'alert-success');
	}

}