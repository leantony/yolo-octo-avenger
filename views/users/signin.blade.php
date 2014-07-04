{{ Form::open(['url'=>'users/signin', 'class'=>'form-horizontal']) }}
<fieldset>
    <legend>Please signin</legend>
    <div class="form-group">
    {{ Form::label('username', 'Username', array('class' => 'col-lg-2 control-label')) }}
      <div class="col-lg-4">
        {{ Form::text('username', null, [ 'class'=>'form-control', 'placeholder'=>'enter your username']) }}
      </div>
    </div>
    <div class="form-group">
      {{ Form::label('password', 'Password', array('class' => 'col-lg-2 control-label')) }}
      <div class="col-lg-4">
        {{ Form::password('password', [ 'class'=>'form-control', 'placeholder'=>'enter your password']) }}
        <div class="checkbox">
          <label>
            {{ Form::checkbox('remember', 'remember', true) }} Remmember me
          </label>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-lg-offset-2">
           {{ Form::submit('Sign In', [ 'class'=>'btn btn-primary']) }}
    </div>
        
 </fieldset>
{{ Form::close() }}