<!-- <ul>
	@foreach($errors->all() as $error)
		<li>{{ $error }}</li>
	@endforeach
</ul> -->
<!-- <div class="form-group has-error">
  <label class="control-label" for="inputError">Input error</label>
  <input class="form-control" id="inputError" type="text">
</div> -->
{{ Form::open(['url'=>'users/signup', 'class'=>'form-horizontal']) }}
<fieldset>
    <legend>Please signup</legend>
    @if ($errors->has('username'))
      <div class="form-group has-error">
        <label class="col-lg-2 control-label" for="inputError">
          {{ $errors->first('username') }}
        </label>
        <div class="col-lg-4">
          {{ Form::text('inputError', null, [ 'class'=>'form-control', 'placeholder'=>'enter a username']) }}
        </div>
      </div>
    @else
      <div class="form-group">
      {{ Form::label('username', 'Username', array('class' => 'col-lg-2 control-label')) }}
        <div class="col-lg-4">
          {{ Form::text('username', null, [ 'class'=>'form-control', 'placeholder'=>'enter a username']) }}
        </div>
      </div>
    @endif
    
    @if($errors->has('password'))
      <div class="form-group has-error">
        <label class="col-lg-2 control-label" for="inputError">
          {{ $errors->first('password') }}
        </label>
        <div class="col-lg-4">
          {{ Form::text('inputError', null, [ 'class'=>'form-control', 'placeholder'=>'enter a password']) }}
        </div>
      </div>
    @else
      <div class="form-group">
        {{ Form::label('password', 'Password', array('class' => 'col-lg-2 control-label')) }}
        <div class="col-lg-4">
          {{ Form::password('password', [ 'class'=>'form-control', 'placeholder'=>'enter a password']) }}
          
        </div>
      </div>
    @endif
    
    @if($errors->has('password_confirmation'))
      <div class="form-group has-error">
          <label class="col-lg-2 control-label" for="inputError">
            {{ $errors->first('password_confirmation') }}
          </label>
          <div class="col-lg-4">
            {{ Form::text('inputError', null, [ 'class'=>'form-control', 'placeholder'=>'repeat entered password']) }}
          </div>
      </div>
    @else
      <div class="form-group">
                {{ Form::label('password_confirmation', 'Repeat Password', array('class' => 'col-lg-2 control-label')) }}
        <div class="col-lg-4">
              {{ Form::password('password_confirmation', [ 'class'=>'form-control', 'placeholder'=>'repeat entered password']) }}
        </div>
      </div>
    @endif
    
    <div class="col-lg-4 col-lg-offset-2">
           {{ Form::submit('Sign Up', [ 'class'=>'btn btn-primary']) }}
    </div>
        
 </fieldset>
{{ Form::close() }}