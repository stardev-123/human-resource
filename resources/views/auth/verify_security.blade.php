@extends('layouts.guest')

@section('content')

<div class="full-content-center animated fadeInDownBig">

    {!! getCompanyLogo() !!}
    
    <div class="login-wrap">
        <div class="box-info">
        <h2 class="text-center"><strong>{{trans('messages.verify')}}</strong> {{trans('messages.security')}}</h2>
            {!! Form::open(['route' => 'verify-security','role' => 'form', 'class'=>'two-factor-auth-form','id' => 'two-factor-auth-form','data-redirect-duration' => 0]) !!}
                <div class="row">
                    <div class="col-md-2">
                        {!! getAvatar(Auth::user()->id,60) !!}
                    </div>
                    <div class="col-md-10">
                        <div class="form-group">
                            {!! Form::label('login',((config('config.login_type') == 'email') ? Auth::user()->email : Auth::user()->full_name),[])!!}
                            {!! Form::input('text','two_factor_auth',(!getEnvironment()) ? session('two_factor_auth') : '',['class'=>'form-control','placeholder'=>'Two Factor Auth','autofocus' => 'autofocus'])!!}
                        </div>
                        {!! Form::submit(trans('messages.verify'),['class' => 'btn btn-primary']) !!}
                        <a href="#" class="btn btn-danger" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">Not {{Auth::user()->full_name}}? Logout?</a>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<form id="logout-form" action="/logout" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>

@endsection