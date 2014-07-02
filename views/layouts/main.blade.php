<!DOCTYPE html>
<html lang="en">
 	<head>
    	<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">

    	<title>sample laravel codesharing app</title>

    	{{ HTML::style('css/bootstrap.min.css') }}
    	{{ HTML::style('css/main.css')}}

    	{{ HTML::script('https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js') }}
  	</head>

  	<body>

	  	<div class="navbar navbar-fixed-top">
		  	<div class="navbar-inner">
		    	<div class="container">
					<ul class="nav">  
						<li>{{ HTML::link('/', 'Home') }}</li>
						@if(!Auth::check())
							<li>{{ HTML::link('users/signup', 'Sign Up') }}</li>   
							<li>{{ HTML::link('users/signin', 'Sign In') }}</li>   
						@else
							<li>{{ HTML::link('users/signout', 'Sign Out') }}</li>
							<li>{{ HTML::link('favorites/index', 'My Favorites') }}</li>
						@endif
					</ul>  
		    	</div>
		  	</div>
		</div> 	            

	    <div class="container container-box">

	  		<div class="container">
	  			<h1>welcome to codeshare</h1>
	  		</div>

	    	@if(Session::has('message'))
				<p class="alert">{{ Session::get('message') }}</p>
			@endif

	    	{{ $content }}
	    </div>

  	</body>
</html>