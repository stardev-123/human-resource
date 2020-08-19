@extends('layouts.guest')

@section('content')



<div class="full-content-center animated fadeInDownBig">

    {!! getCompanyLogo() !!}
    
    @if(!getMode())
        <br />
        <br />
        @include('global.notification',['message' => 'Most awaited Version 3.2 Released with new features on 1st April 2017. <a href="#" data-href="/whats-new" data-toggle="modal" data-target="#myModal">Click here to check Whats New?</a>' ,'type' => 'success'])
    @endif
    
    <div class="login-wrap">
        <div class="box-info">
        <h2 class="text-center"><strong>{{trans('messages.user')}}</strong> {{trans('messages.login')}}</h2>
            <form role="form" action="{!! URL::to('/login') !!}" method="post" class="login-form" id="login-form" data-redirect-msg="{{trans('messages.login_redirect_message')}}" data-redirect-duration="10">
                {!! csrf_field() !!}
                @if(config('config.login_type') == 'email')
                    <div class="form-group login-input">
                    <i class="fa fa-envelope overlay"></i>
                    <input type="email" class="form-control text-input" name="email" placeholder="{{trans('messages.email')}}">
                    </div>
                @elseif(config('config.login_type') == 'username')
                    <div class="form-group login-input">
                    <i class="fa fa-user overlay"></i>
                    <input type="text" class="form-control text-input" name="username" placeholder="{{trans('messages.username')}}">
                    </div>
                @else
                    <div class="form-group login-input">
                    <i class="fa fa-user overlay"></i>
                    <input type="text" class="form-control text-input" name="email" placeholder="{{trans('messages.username').' '.trans('messages.or').' '.trans('messages.email')}}">
                    </div>
                @endif
                <div class="form-group login-input">
                <i class="fa fa-key overlay"></i>
                <input type="password" class="form-control text-input" name="password" placeholder="{{trans('messages.password')}}">
                </div>
                @if(config('config.enable_remember_me'))
                    <div class="checkbox">
                        <label>
                        <input type="checkbox" class="icheck" name="remember" value="1"> {{trans('messages.remember').' '.trans('messages.me')}}
                        </label>
                    </div>
                @endif
                @if(config('config.enable_recaptcha') && config('config.enable_recaptcha_login'))
                <div class="g-recaptcha" data-sitekey="{{config('config.recaptcha_key')}}"></div>
                <br />
                @endif
                
                <div class="row">
                    @if(config('config.enable_user_registration'))
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-success btn-block"><i class="fa fa-unlock"></i> {{trans('messages.login')}}</button>
                        </div>
                        <div class="col-sm-6">
                            <a href="/register" class="btn btn-info btn-block"><i class="fa fa-user-plus"></i> {{trans('messages.register')}}</a>
                        </div>
                    @else
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-success btn-block"><i class="fa fa-unlock"></i> {{trans('messages.login')}}</button>
                        </div>
                    @endif
                </div>
            </form>
            @if(config('config.enable_social_login'))
            <hr>
            <div class="text-center">
                @foreach(config('constant.social_login_provider') as $provider)
                    @if(config('config.enable_'.$provider.'_login'))
                    <a class="btn btn-social btn-{{$provider.(($provider == 'google') ? '-plus' : '')}}" href="/auth/{{$provider}}" style="margin-bottom: 5px;">
                        <i class="fa fa-{{$provider}}"></i> {{toWord($provider)}}
                    </a>
                    @endif
                @endforeach
            </div>
            @endif
            @if(!getMode())
            <div class="row" style="margin-bottom: 15px;">
                <h4 class="text-center">For Demo Purpose Login As</h4>
                <div class="col-md-4">
                    <a href="#" data-username="john.doe" data-email="john.doe@example.com" data-password="abcd1234" class="btn btn-block btn-primary login-as"><small>Admin</small></a>
                </div>
                <div class="col-md-4">
                    <a href="#" data-username="marry.jen" data-email="marry.jen@example.com" data-password="abcd1234" class="btn btn-block btn-danger login-as"><small>Manager</small></a>
                </div>
                <div class="col-md-4">
                    <a href="#" data-username="robert.william" data-email="robert.william@example.com" data-password="abcd1234" class="btn btn-block btn-warning login-as"><small>Staff</small></a>
                </div>
            </div>
            @endif
        </div>
    </div>
    @if(config('config.enable_reset_password'))
    <p class="text-center"><a href="/password/reset"><i class="fa fa-lock"></i> {{trans('messages.forgot').' '.trans('messages.password')}}?</a></p>
    @endif
</div>

@endsection
