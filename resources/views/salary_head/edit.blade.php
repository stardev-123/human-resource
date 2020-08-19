	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.salary').' '.trans('messages.head') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($salary_head,['method' => 'PATCH','route' => ['salary-head.update',$salary_head] ,'class' => 'salary-head-edit-form','id' => 'salary-head-edit-form','data-table-refresh' => 'salary-head-table']) !!}
			@include('salary_head._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>