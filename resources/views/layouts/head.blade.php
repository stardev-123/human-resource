<!DOCTYPE html>
<html>
    <head>
    <title>{!! config('config.application_name') ? : config('constants.default_title') !!}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="Kaber Helm">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    {!! Html::style('css/bootstrap.min.css') !!}
    {!! Html::style('vendor/jquery-ui/jquery-ui.min.css') !!}
    {!! Html::style('css/style.css') !!}

    @if(isset($direction) && $direction == 'rtl')
    {!! Html::style('css/bootstrap-rtl.css') !!}
    {!! Html::style('css/bootstrap-flipped.css') !!}
    {!! Html::style('css/style-right.css') !!}
    @endif

    {!! Html::style('css/style-responsive.css') !!}
    {!! Html::style('css/animate.css') !!}
    {!! Html::style('vendor/toastr/toastr.min.css') !!}

    {!! Html::style('vendor/font-awesome/css/font-awesome.min.css') !!}
    {!! Html::style('vendor/sortable/sortable-theme-bootstrap.css') !!}
    {!! Html::style('vendor/icheck/skins/flat/blue.css') !!}
    {!! Html::style('vendor/select/css/bootstrap-select.min.css') !!}
    {!! Html::style('vendor/switch/bootstrap-switch.min.css') !!}
    {!! Html::style('vendor/datepicker/css/datepicker.css') !!}
    @if(isset($assets) && in_array('datatable',$assets))
        {!! Html::style('vendor/datatables/datatables.min.css') !!}
    @endif
    @if(isset($assets) && in_array('calendar',$assets))
        {!! Html::style('vendor/calendar/fullcalendar.min.css') !!}
    @endif
    @if(isset($assets) && in_array('tags',$assets))
        {!! Html::style('vendor/tags/tags.css') !!}
    @endif
    @if(isset($assets) && in_array('timepicker',$assets))
        {!! Html::style('vendor/timepicker/bootstrap-clockpicker.min.css') !!}
    @endif
    @if(isset($assets) && in_array('datetimepicker',$assets))
        {!! Html::style('vendor/datetimepicker/css/bootstrap-datetimepicker.min.css') !!}
    @endif
    @if(isset($assets) && in_array('slider',$assets))
        {!! Html::style('vendor/slider/bootstrap-slider.min.css') !!}
    @endif
    {!! Html::style('vendor/page/page.css') !!}
    @if(isset($assets) && in_array('summernote',$assets))
        {!! Html::style('vendor/summernote/summernote.css') !!}
    @endif
    @if(isset($assets) && in_array('redactor',$assets))
        {!! Html::style('vendor/redactor/redactor.css') !!}
    @endif
    {!! Html::style('css/custom.css') !!}
    {!! Html::style('css/color-scheme/'.config('config.theme_color').'.css') !!}

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family={{config('config.theme_font')}}">
    <style>
        *{font-family: {{config('config.theme_font')}},'Verdana', 'sans-serif';}
        h2{font-family: {{config('config.theme_font')}},'Verdana', 'sans-serif'; font-weight:bold;}
    </style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- <link rel="shortcut icon" href="{!! url('images/favicon.ico') !!}"> -->
    </head>
