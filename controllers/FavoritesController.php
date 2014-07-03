<?php

class FavoritesController extends \BaseController {

	public function __construct()
	{
		$this->beforeFilter('csrf', ['on'=>'post']);
		// protect create & index actions
		//$this->beforeFilter('auth', ['only'=> array('index', 'create')]);
	}
	/**
	 * Display a listing of the resource.
	 * GET /favorites
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		// retrieve all favorite snippets for the current user
		// $userid = Auth::user()->id;
		// $username = Auth::user()->username;
		// $userFavs = User::find($userid)->favorites;
		$this->layout->content = View::make('favorites.index')->with('favorites',
			User::find(Auth::user()->id)->favorites);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /favorites/create
	 *
	 * @return Response
	 */
	public function postCreate()
	{
		$current_user_favorites = User::find(Auth::user()->id)->favorites;
		// only signed in users can fave snippets
		if (Auth::check()) {

			$favorite = new Favorite;
			$favorite->user_id = Auth::user()->id;
			$favorite->snippet_id = Input::get('snippet_id');
			// check if the favorite to be marked has already been marked
			foreach ($current_user_favorites as $favs) {
				if ($favs->snippet->id === $snippet_id) {
					return Redirect::to('favorites')->with('message', 'you already have that
						snippet in your favorites. select another')->with('alertclass',
				'alert-warning');
				}
			}
			$favorite->save();

			return Redirect::to('favorites')->with('message', 'You\'ve
				successfully added the code snippet to your favorites')->with('alertclass',
				'alert-success');
		} else {

			return Redirect::to('users/signin')->with('message', 'You have to signup/signin
				to fave a snippet')->with('alertclass', 'alert-warning');
		}
	}
}