@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li><a href="/expense">{!! trans('messages.expense') !!}</a></li>
		    <li class="active">{{$expense->ExpenseHead->name.' : '.$expense->User->name_with_designation_and_department}}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-md-4">
				<div class="box-info full">
					<h2><strong>{{trans('messages.expense').' '.trans('messages.detail')}}</strong></h2>
					<div id="load-expense-detail" data-extra="&id={{$expense->id}}" data-source="/expense/detail"></div>
				</div>
			</div>
			<div class="col-md-8">
				@if($expense_status_enabled)
					<div class="box-info">
					{!! Form::model($expense,['method' => 'POST','route' => ['expense.update-status',$expense->id] ,'class' => 'expense-status-form','id' => 'expense-status-form','data-no-form-clear' => 1,'data-table-refresh' => 'expense-status-detail-table','data-refresh' => 'load-expense-detail']) !!}
						<h2><strong>{!! trans('messages.update') !!}</strong> {!! trans('messages.status') !!}</h2>
						  <div class="form-group">
						    {!! Form::label('status',trans('messages.expense').' '.trans('messages.status'),[])!!}
							{!! Form::select('status', [
									'pending' => trans('messages.pending'),
									'approved' => trans('messages.w_approved'),
									'rejected' => trans('messages.w_rejected'),
								] , isset($expense_status_detail->status) ?  $expense_status_detail->status : '',['class'=>'form-control show-tick','id' => 'status'])!!}
						  </div>
						  <div class="form-group">
						    {!! Form::label('remarks',trans('messages.remarks'),[])!!}
						    {!! Form::textarea('remarks',isset($expense_status_detail->remarks) ? $expense_status_detail->remarks : '',['size' => '30x3', 'class' => 'form-control', 'placeholder' => trans('messages.remarks'),"data-show-counter" => 1,"data-limit" => config('config.textarea_limit'),'data-autoresize' => 1])!!}
						    <span class="countdown"></span>
						  </div>
						  {!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
					{!! Form::close() !!}
					</div>
				@endif
				<div class="box-info full">
					<h2><strong>{!! trans('messages.expense') !!}</strong> {!! trans('messages.status') !!}</h2>
					<div class="table-responsive">
						<table class="table table-stripped table-hover ajax-table" id="expense-status-detail-table" data-extra="&id={{$expense->id}}" data-source="/expense-status-detail">
							<thead>
								<tr>
									<th>{{trans('messages.designation')}}</th>
									<th>{{trans('messages.status')}}</th>
									<th>{{trans('messages.remarks')}}</th>
									<th>{{trans('messages.updated_at')}}</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	@endsection