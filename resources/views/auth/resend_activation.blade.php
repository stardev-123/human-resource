@extends('layouts.guest')

@section('content')

<div class="full-content-center animated fadeInDownBig">

    {!! getCompanyLogo() !!}
    
    <div class="login-wrap">
        <div class="box-info">
        <h2 class="text-center"><strong>{{trans('messages.resend')}}</strong> {{trans('messages.activation').' '.trans('messages.mail')}}</h2>
            <form role="form" action="{!! URL::to('/resend-activation') !!}" method="post" class="resend-activation-form" id="resend-activation-form">
                {!! csrf_field() !!}
                <div class="form-group login-input">
                    <i class="fa fa-envelope overlay"></i>
                    <input type="email" class="form-control text-input" name="email" placeholder="{{trans('messages.email')}}">
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-success btn-block"><i class="fa fa-envelope"></i> {{trans('messages.resend').' '.trans('messages.mail')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <p class="text-center"><a href="/login"><i class="fa fa-unlock"></i> {{trans('messages.back_to').' '.trans('messages.login')}}</a></p>
</div>

@endsection