	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.add_new').' '.trans('messages.salary').' '.trans('messages.head') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::open(['route' => 'salary-head.store','role' => 'form', 'class'=>'salary-head-form','id' => 'salary-head-form']) !!}
			@include('salary_head._form')
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>