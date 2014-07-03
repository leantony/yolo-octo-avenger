<ul>
	@foreach($errors->all() as $error)
		<li>{{ $error }}</li>
	@endforeach
</ul>
{{ Form::open(['url'=>'users/signup', 'class'=>'form-horizontal']) }}
<fieldset>
    <legend>Please signup</legend>
    <div class="form-group">
    {{ Form::label('username', 'Username', array('class' => 'col-lg-2 control-label')) }}
      <div class="col-lg-4">
        {{ Form::text('username', null, [ 'class'=>'form-control', 'placeholder'=>'enter a username']) }}
      </div>
    </div>
    <div class="form-group">
      {{ Form::label('password', 'Password', array('class' => 'col-lg-2 control-label')) }}
      <div class="col-lg-4">
        {{ Form::password('password', [ 'class'=>'form-control', 'placeholder'=>'enter a password']) }}
        
      </div>
    </div>
    <div class="form-group">
    	      	{{ Form::label('password_confirmation', 'Repeat Password', array('class' => 'col-lg-2 control-label')) }}
    	<div class="col-lg-4">
    	  		{{ Form::password('password_confirmation', [ 'class'=>'form-control', 'placeholder'=>'repeat entered password']) }}
    	</div>
    </div>
    <div class="col-lg-4 col-lg-offset-2">
           {{ Form::submit('Sign Up', [ 'class'=>'btn btn-primary']) }}
    </div>
        
 </fieldset>
    {{ Form::close() }}