<h2>{{ $username }}'s favorites</h2>

<ul>
@foreach ($favorites as $favorite)
	
	<li>
		{{ HTML::link('snippets/view/'.$favorite->snippet->slug, $favorite->snippet->name) }}
	</li>
@endforeach

</ul>