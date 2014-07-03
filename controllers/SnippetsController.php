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
			// a slug is simply a short name given to an article. so we let the user
			// use it's slug as a url name for easy access
			$snippet->slug = Str::slug(Input::get('name'));
			$snippet->save();

			//redirect the user to their snippet using the slug
			$slug = Snippet::where('name', '=', Input::get('name'))->first()->slug;

			return Redirect::to('snippets/view/'.$slug)
			->with('message', 'Your code snippet has been successfully saved')->with('alertclass',
				'alert-success');

		} else {
			return Redirect::to('snippets/index')
			->with('message', $this->errormsg)->with('alertclass', 'alert-danger')
			->withErrors($validator)->withInput();  
		}
	}

	/**
	 * allow users to see their/others snippets
	 * @return Response
	 */
	public function getView($slug)
	{
		// get the first slug
		// eeeew just call it url
		$data = Snippet::where('slug', '=', $slug)->first();
		$this->layout->content = View::make('snippets.view')->with('snippet', $data);
	}

}