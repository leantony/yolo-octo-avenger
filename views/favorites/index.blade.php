<h2>Here are your favorite snippets</h2>

<ul>
	@foreach ($favorites as $favorite)
		
		<li>
			{{ HTML::link('snippets/view/'.$favorite->snippet->slug, $favorite->snippet->name) }}
		</li>
	@endforeach
	
</ul>