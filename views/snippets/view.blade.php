<h2>Snippet: {{ $snippet->name }}</h2>
<p>
	share it: <a href="http://localhost:8000/snippets/view/{{ $snippet->slug }}">http://localhost:8000/snippets/view/{{ $snippet->slug }}</a>
	
</p>


<pre class="prettyprint">
{{{ $snippet->code }}}
</pre>
