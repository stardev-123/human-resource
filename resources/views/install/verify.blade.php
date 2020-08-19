@extends('layouts.guest')

@section('content')

<div class="full-content-center animated fadeInDownBig">

    {!! getCompanyLogo() !!}
    
    <div class="login-wrap">
        <div class="box-info">
        <h2 class="text-center"><strong>{{trans('messages.verify')}}</strong> {{trans('messages.purchase')}}</h2>
            <form role="form" action="{!! URL::to('/verify-purchase') !!}" method="post" class="verify-purchase-form" id="verify-purchase-form">
                {!! csrf_field() !!}
                <div class="form-group">
                <input type="text" name="envato_username" id="envato_username" class="form-control text-input" placeholder="Envato Username">
                </div>
                <div class="form-group">
                <input type="text" name="purchase_code" id="purchase_code" class="form-control text-input" placeholder="Purchase Code">
                </div>
                <div class="row">
                    <div class="col-sm-12">
                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-unlock"></i> Verify</button>
                    </div>
                </div>
            </form>
            <p class="text-center"><a href="{!! URL::to('/') !!}"><i class="fa fa-lock"></i> {!! trans('messages.back_to').' '.trans('messages.login') !!}</a></p>
        </div>
    </div>
</div>
<form id="logout-form" action="/logout" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>

@endsection