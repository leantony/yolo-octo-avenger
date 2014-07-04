<?php

class FavoritesController extends \BaseController {

	public function __construct()
	{
		$this->beforeFilter('csrf', ['on'=>'post']);
		// protect create & index actions
		$this->beforeFilter('auth', ['only'=> array('index', 'create')]);
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
		$userid = Auth::user()->id;
		$username = Auth::user()->username;
		$userFavsCount = User::find($userid)->favorites->count();

		if (is_null($userFavsCount) || $userFavsCount == 0) {
			// a user should recieve a msg if they don't have any favorites
			return Redirect::to('/')->with('message', 'you don\'t seem to have
				any favorites yet. To mark a snippet as favorite click the \'fave snippet\' button next to the snippet' )->with('alertclass',
				'alert-warning');
		} else {
			$this->layout->content = View::make('favorites.index')->with('favorites',
				User::find(Auth::user()->id)->favorites)->with('username', $username);
		}
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /favorites/create
	 *
	 * @return Response
	 */
	public function postCreate()
	{
		// only signed in users can fave snippets
		if (Auth::check() || Auth::viaRemember()) {

			$favorite             = new Favorite;
			$favorite->user_id    = Auth::user()->id;
			$favorite->snippet_id = Input::get('snippet_id');
			// check if the favorite to be marked has already been marked by current usr
			// SELECT count('snippet_id') as favorite_count FROM `favorites` WHERE user_id = 1 and snippet_id = 1
			$uniq_fav_count = Favorite::whereRaw('snippet_id = ? and user_id = ?', [$favorite->snippet_id,
				$favorite->user_id] )->count();

			if ($uniq_fav_count >= 1) {
				# this one exists so alert user
				return Redirect::to('favorites')->with('message', 'It seems that you\'ve already
					marked this snippet as a favorite before. select another')->with('alertclass',
				'alert-warning');
			} else {
				# this one is a new one so save it n redirect
				$favorite->save();
				return Redirect::to('favorites/index')->with('message', 'You\'ve
				successfully added the code snippet to your favorites')->with('alertclass',
				'alert-success');
			}
				
		} else {

			return Redirect::intended('users/signin')->with('message', 'You have to signup/signin
				to fave a snippet')->with('alertclass', 'alert-warning');
		}
	}
}