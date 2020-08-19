@extends('layouts.guest')
    @section('content')
		<div class="full-content-center animated bounceIn">
	    	
    		{!! getCompanyLogo() !!}
    
			<h2>{{trans('messages.error')}}</h2>
			<p>{{trans('messages.under_maintenance_message')}}</p>
		</div>
		@include('layouts.footer')
	@stop
