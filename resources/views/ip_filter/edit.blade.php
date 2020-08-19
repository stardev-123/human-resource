
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title">{!! trans('messages.edit').' IP '.trans('messages.filter') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($ip_filter,['method' => 'PATCH','route' => ['ip-filter.update',$ip_filter->id] ,'class' => 'ip-filter-form','id' => 'ip-filter-form-edit']) !!}
			@include('ip_filter._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clearfix"></div>
	</div>