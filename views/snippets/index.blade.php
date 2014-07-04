<!-- <ul>
  @foreach($errors->all() as $error)
    <li>{{ $error }}</li>
  @endforeach
</ul> -->
<!-- <div class="form-group has-error">
  <label class="control-label" for="inputError">Input error</label>
  <input class="form-control" id="inputError" type="text">
</div> -->
{{ Form::open(['url'=>'snippets/create', 'class'=>'form-horizontal']) }}
	<fieldset>
		<legend>Welcome :) To get started, Save a code snippet below</legend>
		@if ($errors->has('name'))
			<div class="form-group has-error">
				<label class="col-lg-2 control-label" for="inputError">
					{{ $errors->first('name') }}
				</label>
					<div class="col-lg-4">
					  {{ Form::text('inputError', null, [ 'class'=>'form-control', 'placeholder'=>'enter the name of your snippet']) }}
					  
					</div>
			</div>
		@else
			<div class="form-group">
				{{ Form::label('name', 'Snippet name', array('class' => 'col-lg-2 control-label')) }}
					<div class="col-lg-4">
					  {{ Form::text('name', null, [ 'class'=>'form-control', 'placeholder'=>'enter the name of your snippet']) }}
					</div>
			</div>
		
		@endif

			@if ($errors->has('code')) 
				<div class="form-group has-error">
				  <label class="col-lg-2 control-label" for="inputError">
				  	{{ $errors->first('code') }}
				  </label>
				  <div class="col-lg-6">
				  	{{ Form::textarea('inputError', null, [ 'rows' => 6,'class'=>'form-control', 'placeholder'=>'enter/paste the your code snippet']) }}
				  </div>
				  
				</div>
			@else
				<div class="form-group">
					{{ Form::label('code', 'Snippet code', array('class' => 'col-lg-2 control-label')) }}
					<div class="col-lg-6">
						{{ Form::textarea('code', null, [ 'rows' => 6,'class'=>'form-control', 'placeholder'=>'enter/paste the your code snippet']) }}
					</div>
				</div>
				
			@endif
		
			@if($errors->has('lang'))
				<label class="col-lg-2 control-label" for="inputError">
					{{ $errors->first('lang') }}
				</label>
			@else
				<div class="form-group">
				{{ Form::label('lang', 'Select code language', array('class' => 'col-lg-2 control-label')) }}
					<div class="col-lg-4">
						{{ Form::select('lang', [ 
							'php'    =>'PHP',
							'js'     =>'Js',
							'ruby'   =>'Ruby',
							'pyhton' =>'python',
							'html'   =>'HTML'
						], 
						[ 'class'=>'form-control', 'placeholder'=>'select language']) }}
					</div>
				</div>
			@endif
		
		<div class="col-lg-3">
		        {{ Form::submit('save snippet', [ 'class'=>'btn btn-primary']) }}
		</div>
	</fieldset>
{{ Form::close() }}
<hr>
<!-- display most recent snippets -->
<div class="container">
	<h2>Recently shared code snippets</h2>
	<p>You can find 5 of the most recently shared snippets below</p>
	<ul>
		@foreach ($snippets as $snippet)
			<li>
				{{ HTML::link('snippets/view/'.$snippet->slug, $snippet->name) }}
					- {{ Form::open( ['url'=>'favorites/create', 'class'=>'form']) }}
					{{ Form::hidden('snippet_id', $snippet->id) }}
					{{ Form::submit('fave snippet', [ 'class'=>'btn btn-default btn-xs']) }}
					{{ Form::close() }}
			</li>
		@endforeach
	</ul>
</div>