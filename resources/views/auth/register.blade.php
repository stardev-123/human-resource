@extends('layouts.guest')

@section('content')

<div class="full-content-center animated fadeInDownBig">

    {!! getCompanyLogo() !!}
    
    <div class="login-wrap">
        <div class="box-info">
        <h2 class="text-center"><strong>{{trans('messages.register')}}</strong></h2>
            <form role="form" action="{!! URL::to('/register') !!}" method="post" class="user-registration-form" id="user-registration-form">
                {!! csrf_field() !!}
                <div class="form-group login-input">
                    <i class="fa fa-envelope overlay"></i>
                    <input type="email" class="form-control text-input" name="email" placeholder="{{trans('messages.email')}}">
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <input type="text" class="form-control text-input" name="first_name" placeholder="{{trans('messages.first').' '.trans('messages.name')}}">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control text-input" name="last_name" placeholder="{{trans('messages.last').' '.trans('messages.name')}}">
                        </div>
                    </div>
                </div>
                <div class="form-group login-input">
                    <i class="fa fa-user overlay"></i>
                    <input type="text" class="form-control text-input" name="username" placeholder="{{trans('messages.username')}}">
                </div>
                <div class="form-group login-input">
                    <i class="fa fa-key overlay"></i>
                    <input type="password" class="form-control text-input @if(config('config.enable_password_strength_meter')) password-strength @endif" name="password" placeholder="{{trans('messages.password')}}">
                </div>
                <div class="form-group login-input">
                    <i class="fa fa-key overlay"></i>
                    <input type="password" class="form-control text-input" name="password_confirmation" placeholder="{{trans('messages.confirm').' '.trans('messages.password')}}">
                </div>
                {{ getCustomFields('user-registration-form') }}
                @if(Auth::check())
                <div class="form-group">
                    <input name="send_welcome_email" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1"> {{trans('messages.send')}} welcome email
                </div>
                @endif
                @if(config('config.enable_tnc') && !Auth::check())
                <div class="form-group">
                    <input name="tnc" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1"> I accept <a href="#" data-href="/terms-and-conditions" data-toggle="modal" data-target="#myModal">Terms & Conditions</a>.
                </div>
                @endif
                @if(config('config.enable_recaptcha') && config('config.enable_recaptcha_registration') && !Auth::check())
                <div class="g-recaptcha" data-sitekey="{{config('config.recaptcha_key')}}"></div>
                <br />
                @endif
                <div class="row">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-success btn-block"><i class="fa fa-lock"></i> {{trans('messages.create').' '.trans('messages.account')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <p class="text-center"><a href="/login"><i class="fa fa-unlock"></i> {{trans('messages.back_to').' '.trans('messages.login')}}</a></p>
</div>

@endsection