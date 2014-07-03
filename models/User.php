<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	public static $rules = [
	'username' => 'required|alpha_num|between:2,8|unique:users',
	'password' => 'required|alpha_num|between:6,12|confirmed',
	'password_confirmation' => 'required|alpha_num|between:6,12|',

	];

	// a user can have one or many favorites
	public function favorites($value='')
	{
		return $this->hasMany('Favorite');
	}
}
