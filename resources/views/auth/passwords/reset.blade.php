@extends('layouts.guest')

@section('content')

<div class="full-content-center animated fadeInDownBig">

    {!! getCompanyLogo() !!}
    
    <div class="login-wrap">
        <div class="box-info">
        <h2 class="text-center"><strong>{{trans('messages.reset')}}</strong> {{trans('messages.password')}}</h2>
            <form role="form" action="{!! URL::to('/password/reset') !!}" method="post" class="password-reset-form" id="password-reset-form">
                <input type="hidden" name="token" value="{{ $token }}">
                {!! csrf_field() !!}
                <div class="form-group login-input">
                    <i class="fa fa-envelope overlay"></i>
                    <input type="email" class="form-control text-input" name="email" placeholder="{{trans('messages.email')}}">
                </div>
                <div class="form-group login-input">
                    <i class="fa fa-key overlay"></i>
                    <input type="password" class="form-control text-input @if(config('config.enable_password_strength_meter')) password-strength @endif" name="password" placeholder="{{trans('messages.password')}}">
                </div>
                <div class="form-group login-input">
                    <i class="fa fa-key overlay"></i>
                    <input type="password" class="form-control text-input" name="password_confirmation" placeholder="{{trans('messages.confirm').' '.trans('messages.password')}}">
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-success btn-block"><i class="fa fa-lock"></i> {{trans('messages.reset').' '.trans('messages.password')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <p class="text-center"><a href="/login"><i class="fa fa-unlock"></i> {{trans('messages.back_to').' '.trans('messages.login')}}</a></p>
</div>

@endsection