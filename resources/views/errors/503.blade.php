@extends('layouts.guest')
    @section('content')
        <div class="full-content-center animated bounceIn">
            
            {!! getCompanyLogo() !!}
    
            <h2>{{trans('messages.error')}}</h2>
            <p>{{trans('messages.error_message')}}</p>
            <p>{{trans('messages.back_to')}} <a href="/home">{{trans('messages.home')}}</a></p>
        </div>
    @stop
