{{ Form::open(['url'=>'users/signin', 'class'=>'form-signin']) }}
	<h2 class="form-signin-heading">Sign In</h2>
	{{ Form::text('username', null, [ 'class'=>'input-block-level', 'placeholder'=>'enter a username']) }}
	{{ Form::password('password', [ 'class'=>'input-block-level', 'placeholder'=>'enter a password']) }}
{{ Form::submit('Sign In', [ 'class'=>'btn btn-large-primary btn-block']) }}
{{ Form::close() }}