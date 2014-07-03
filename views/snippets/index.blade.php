<ul>
  @foreach($errors->all() as $error)
    <li>{{ $error }}</li>
  @endforeach
</ul>
{{ Form::open(['url'=>'snippets/create', 'class'=>'form-horizontal']) }}
	<fieldset>
		<legend>Save a code snippet</legend>
	<div class="form-group">
		{{ Form::label('name', 'Snippet name', array('class' => 'col-lg-2 control-label')) }}
			  <div class="col-lg-4">
			  		{{ Form::text('name', null, [ 'class'=>'form-control', 'placeholder'=>'enter the name of your snippet']) }}
			  </div>
		</div>
		
		<div class="form-group">
		{{ Form::label('code', 'Snippet code', array('class' => 'col-lg-2 control-label')) }}
			<div class="col-lg-6">
				{{ Form::textarea('code', null, [ 'rows' => 6,'class'=>'form-control', 'placeholder'=>'enter/paste the your code snippet']) }}
			</div>
		</div>
		
		<div class="form-group">
		{{ Form::label('lang', 'Select code language', array('class' => 'col-lg-2 control-label')) }}
			<div class="col-lg-4">
				{{ Form::select('lang', [ 
					'php'=>'PHP',
					'js'=>'Js',
					'ruby'=>'Ruby',
					'pyhton'=>'python'
				], 
				[ 'class'=>'form-control', 'placeholder'=>'select language']) }}
			</div>
		</div>
		
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