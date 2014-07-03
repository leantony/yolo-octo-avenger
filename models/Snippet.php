<?php

class Snippet extends \Eloquent {
	protected $fillable = ['name', 'code', 'slug', 'language'];

	/**
	 * validation of the name, code and the language
	 * */
	public static $rules = [
		'name'=>'required|alpha_dash|min:2|unique:snippets',
		'code'=>'required',
		'lang'=>'required|in:php,js,ruby,python'
	];

	// defines the inverse of the code-snippet relationship
	// a snippet can have one or many favorites
	public function favorites()
	{
		return $this->hasMany('Favorite');
	}
}