@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		</ul>
	@stop

	@section('content')
		<div class="row">
			<div class="col-md-12">
				<div class="box-info">
					<h2><strong>Real Time Notification Demo</strong></h2>
					<p>To check Real Time Notification, open <a href="/">http://</a> in any other browser say Firefox or  Safari, Login as other user and then click on the below link:</p>

					<p>You can choose notification tone and the module where notifications will be sent to the users. Notification setting is available at <a href="/configuration">here</a>.

					<a href="#" data-source="/generate-real-time-notification" data-ajax="1" class="btn btn-primary"> Send Notification </a>
				</div>
			</div>
		</div>
	@stop
