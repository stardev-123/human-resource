@extends('layouts.guest')

@section('content')

<div class="full-content-center animated fadeInDownBig">

    {!! getCompanyLogo() !!}
    
    <div class="login-wrap">
        <div class="box-info">
        <h2 class="text-center"><strong>{{trans('messages.forgot')}}</strong> {{trans('messages.password')}}</h2>
            <form role="form" action="{!! URL::to('/password/email') !!}" method="post" class="password-reset-email-form" id="password-reset-email-form">
                {!! csrf_field() !!}
                <div class="form-group login-input">
                    <i class="fa fa-envelope overlay"></i>
                    <input type="email" class="form-control text-input" name="email" placeholder="{{trans('messages.email')}}">
                </div>
                @if(config('config.enable_recaptcha') && config('config.enable_recaptcha_reset_password'))
                <div class="g-recaptcha" data-sitekey="{{config('config.recaptcha_key')}}"></div>
                <br />
                @endif
                <div class="row">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-success btn-block"><i class="fa fa-envelope"></i> {{trans('messages.send').' '.trans('messages.password').' '.trans('messages.reset').' '.trans('messages.link')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <p class="text-center"><a href="/login"><i class="fa fa-unlock"></i> {{trans('messages.back_to').' '.trans('messages.login')}}</a></p>
</div>

@endsection