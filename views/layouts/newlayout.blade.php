<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{ HTML::style('css/bootstrap3/themes/bootstrap-yeti.css') }}
        <!--{{ HTML::style('//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css') }}
        {{ HTML::style('//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css')
}}-->

        <style>
            body {
                padding-top: 0px;
                margin-top: 50px;
                padding-bottom: 20px;
                font-size: 14px;
            }
        </style>
        {{ HTML::style('css/main.css') }}
        
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <div class="navbar navbar-default navbar-fixed-top">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">CodeShare !!! {{{ '</>'}}}</a>
          </div>
          <div class="navbar-collapse collapse navbar-responsive-collapse">
            <ul class="nav nav-pills navbar-default">
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

    <div class="container">
      <!-- Example row of columns -->
        <div class="row">
        <br>
                @if(Session::has('message') || Session::has('alertclass'))
                  <div class="alert alert-dismissable {{ Session::get('alertclass')}}">
                    <button type="button" class="close"
                    data-dismiss="alert">&times;</button>
                    <strong> {{ Session::get('message') }} </strong>
                  </div>
                @endif
        </div>

        <!-- <div class="jumbotron">
          <h1>Jumbotron</h1>
          <p>This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
          <p><a class="btn btn-primary btn-lg">Learn more</a></p>
        </div> -->
        <div class="col-lg-12">
          {{ $content }}
        </div>


      <hr>

      <footer>
        <p>&copy; Company 2014</p>
      </footer>
    </div> <!-- /container -->
    </body>

    {{ HTML::script('https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.js') }}
    {{ HTML::script('//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js') }}
</html>
