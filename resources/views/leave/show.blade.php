@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li><a href="/leave">{!! trans('messages.leave') !!}</a></li>
		    <li class="active">{{$leave->LeaveType->name.' : '.$leave->User->name_with_designation_and_department}}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-md-4">
				<div class="box-info full">
					<h2><strong>{{trans('messages.leave').' '.trans('messages.detail')}}</strong></h2>
					<div id="load-leave-detail" data-extra="&id={{$leave->id}}" data-source="/leave/detail"></div>
				</div>
			</div>
			<div class="col-md-8">
				@if($leave_status_enabled)
					<div class="box-info">
					{!! Form::model($leave,['method' => 'POST','route' => ['leave.update-status',$leave->id] ,'class' => 'leave-status-form','id' => 'leave-status-form','data-no-form-clear' => 1,'data-table-refresh' => 'leave-status-detail-table','data-refresh' => 'load-leave-detail']) !!}
						<h2><strong>{!! trans('messages.update') !!}</strong> {!! trans('messages.status') !!}</h2>
						  <div class="form-group">
						    {!! Form::label('status',trans('messages.leave').' '.trans('messages.status'),[])!!}
							{!! Form::select('status', [
									'pending' => trans('messages.pending'),
									'approved' => trans('messages.w_approved'),
									'rejected' => trans('messages.w_rejected'),
								] , isset($leave_status_detail->status) ?  $leave_status_detail->status : '',['class'=>'form-control show-tick','id' => 'status'])!!}
						  </div>
						  <div class="form-group leave_date_approved">
						    {!! Form::label('date_approved',trans('messages.date'),[])!!}
							{!! Form::input('text','date_approved',isset($leave_status_detail->date_approved) ? $leave_status_detail->date_approved : '',['class'=>'form-control mdatepicker','placeholder'=>trans('messages.date'),'readonly' => 'true'])!!}
						  </div>
						  <div class="form-group">
						    {!! Form::label('remarks',trans('messages.remarks'),[])!!}
						    {!! Form::textarea('remarks',isset($leave_status_detail->remarks) ? $leave_status_detail->remarks : '',['size' => '30x3', 'class' => 'form-control', 'placeholder' => trans('messages.remarks'),"data-show-counter" => 1,"data-limit" => config('config.textarea_limit'),'data-autoresize' => 1])!!}
						    <span class="countdown"></span>
						  </div>
						  {!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
					{!! Form::close() !!}
					</div>
				@endif
				<div class="box-info full">
					<h2><strong>{!! trans('messages.leave') !!}</strong> {!! trans('messages.status') !!}</h2>
					<div class="table-responsive">
						<table class="table table-stripped table-hover ajax-table" id="leave-status-detail-table" data-extra="&id={{$leave->id}}" data-source="/leave-status-detail">
							<thead>
								<tr>
									<th>{{trans('messages.designation')}}</th>
									<th>{{trans('messages.status')}}</th>
									<th>{{trans('messages.date').' '.trans('messages.w_approved')}}</th>
									<th>{{trans('messages.remarks')}}</th>
									<th>{{trans('messages.updated_at')}}</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
	            <div class="box-info full">
	                <h2><strong>{!! trans('messages.current').' '.trans('messages.leave').' </strong>'.trans('messages.status') !!} </h2>
	                <div class="custom-scrollbar">
	                    <div id="load-leave-current-status" data-source="/leave/current-status" data-extra="&date={{$leave->from_date}}&user_id={{$leave->user_id}}"></div>
	                </div>
	            </div>
			</div>
		</div>
	@endsection