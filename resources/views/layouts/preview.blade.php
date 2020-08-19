<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{!! config('config.application_name') ? : config('constants.default_title') !!}</title>

    {!! Html::style('css/bootstrap.min.css') !!}
    {!! Html::style('css/style.css') !!}
    {!! Html::style('css/style-responsive.css') !!}
    {!! Html::style('vendor/font-awesome/css/font-awesome.min.css') !!}
    {!! Html::style('css/animate.css') !!}
    {!! Html::style('vendor/icheck/skins/flat/blue.css') !!}
    {!! Html::style('vendor/datepicker/css/datepicker.css') !!}
    {!! Html::style('css/custom.css') !!}

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
	<div class="container">
	    <div class="content-page">
	        <div class="body content scroll-y"> 
            @yield('content')
	        </div>
	    </div>
	</div>
    {!! Html::script('js/jquery.min.js') !!}
    {!! Html::script('js/bootstrap.min.js') !!}
    {!! Html::script('vendor/icheck/icheck.min.js') !!}
    {!! Html::script('vendor/datepicker/js/bootstrap-datepicker.js') !!}
    {!! Html::script('js/wmlab.js') !!}
  </body>
</html>