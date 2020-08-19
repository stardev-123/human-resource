
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.payroll') !!}</h4>
	</div>
	<div class="modal-body">
		<p>{!! $payroll->User->name_with_designation_and_department.' '.trans('messages.payroll').' '.trans('messages.from').' '.showDate($payroll->from_date).' '.trans('messages.to').' '.showDate($payroll->to_date) !!}</p>
		{!! Form::model('',['method' => 'PATCH','route' => ['payroll.update',$payroll->id] ,'class' => 'payroll-edit-form','id' => 'payroll-edit-form']) !!}
			@include('payroll._form')
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>