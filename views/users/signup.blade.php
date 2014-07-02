{{ Form::open(['url'=>'users/signup', 'class'=>'form-signup']) }}
	<h2 class="form-signup-heading">Please Sign Up</h2>
	<ul>
		@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
	</ul>
	{{ Form::text('username', null, [ 'class'=>'input-block-level', 'placeholder'=>'enter a username']) }}
	{{ Form::password('password', [ 'class'=>'input-block-level', 'placeholder'=>'enter a password']) }}
	{{ Form::password('password_confirmation', [ 'class'=>'input-block-level', 'placeholder'=>'re-enter your password']) }}
{{ Form::submit('Sign Up', [ 'class'=>'btn btn-large-primary btn-block']) }}
{{ Form::close() }}