<?php

class Favorite extends \Eloquent {
	protected $fillable = ['user_id', 'snippet_id'];

	// defines a relationship between a favorite and a user
	// a code marked as favorite belongs to a user
	public function user()
	{
		return $this->belongsTo('User');
	}

	// defines a relationship between a favorite and a snippet
	// a code marked as favorite of course has a corresponding snippet
	public function snippet()
	{
		return $this->belongsTo('Snippet');
	}
}