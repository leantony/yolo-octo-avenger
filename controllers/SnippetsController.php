<?php

class SnippetsController extends \BaseController {

	// just a csrf filter for security
	public function __construct()
	{
		$this->beforeFilter('csrf', ['on'=>'post']);
	}
	
	/**
	 * Display a form for users to create snippets. also display the most recently submitted snippet
	 * GET /snippets
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		// get 5 of the latest submitted snippets
		$recentSnippets = Snippet::take(5)->orderBy('id', 'desc')->get();
		// send em over
		$this->layout->content = View::make('snippets.index')->with('snippets', $recentSnippets);
	}

	/**
	 * allow the user to create a new resource ie snippet
	 * @return Response
	 */
	public function postCreate()
	{
		$validator = Validator::make(Input::all(), Snippet::$rules);
		// check if validation passes
		if ($validator->passes()){
			// create a new snippet
			$snippet = new Snippet;
			$snippet->name = Input::get('name');
			$snippet->code = Input::get('code');
			$snippet->language = Input::get('lang');
			// a slug is simply a short name given to an article. so we convert the snippets
			// name to that
			$snippet->slug = Str::slug(Input::get('name'));
			$snippet->save();

			//redirect the user to their snippet using the slug
			$slug = Snippet::where('name', '=', Input::get('name'))->first()->slug;

			return Redirect::to('snippets/view/'.$slug)->with('message', 'your code has been shared');

		} else {
			return Redirect::to('snippets/index')
			->with('message', 'The following errors have occured:')->withErrors($validator)->withInput();  
		}
	}

}