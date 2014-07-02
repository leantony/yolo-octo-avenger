<div class="code-form">
	{{ Form::open(['url'=>'snippets/create', 'class'=>'form']) }}
		<h2>Share some code</h2>
		<ul>
			@foreach($errors->all() as $error)
				<li>
					{{ $error }}
				</li>
			@endforeach
		</ul>

		{{ Form::text('name', null, [ 'class'=>'input-block-level', 'placeholder'=>'enter the name of your snippet']) }}
		{{ Form::textarea('code', null, [ 'class'=>'input-block-level', 'placeholder'=>'enter the your code snippet']) }}
		{{ Form::select('lang', [ 
			'php'=>'PHP',
			'js'=>'Js',
			'ruby'=>'Ruby',
			'pyhton'=>'python'
		], 
		[ 'class'=>'input-block-level', 'placeholder'=>'select language']) }}
		{{ Form::submit('Share', [ 'class'=>'btn btn-large-primary btn-block']) }}
	{{ Form::close() }}
</div>

<!-- display most recent snippets -->
<div class="code-display">
	<h2>Recent code snippets</h2>
	<p>You can find 5 of the most recently shared snippets below</p>
	<ul>
		@foreach ($snippets as $snippet)
			<li>
				{{ HTML::link('snippets/view/'.$snippet->slug, $snippet->name) }}
			</li>
		@endforeach
	</ul>
</div>