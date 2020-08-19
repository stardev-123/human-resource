@extends('layouts.guest')

@section('content')

<div class="full-content-center animated fadeInDownBig">

    {!! getCompanyLogo() !!}
    
    <div class="login-wrap">
        <div class="box-info">
        <h2 class="text-center"><strong>{{trans('messages.lock_screen')}}</strong> </h2>
            {!! Form::open(['route' => 'unlock','role' => 'form', 'class'=>'unlock-form','id' => 'unlock-form']) !!}
                <div class="row">
                    <div class="col-md-2">
                        {!! getAvatar(Auth::user()->id,60) !!}
                    </div>
                    <div class="col-md-10">
                        <div class="form-group">
                            {!! Form::label('login',(config('config.login')) ? Auth::user()->email : Auth::user()->username,[])!!}
                            {!! Form::input('password','password','',['class'=>'form-control','placeholder'=>trans('messages.password'),'autofocus' => 'autofocus'])!!}
                        </div>
                        {!! Form::submit(trans('messages.unlock'),['class' => 'btn btn-primary']) !!}
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