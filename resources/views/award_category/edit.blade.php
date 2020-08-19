	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.award').' '.trans('messages.category') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($award_category,['method' => 'PATCH','route' => ['award-category.update',$award_category] ,'class' => 'award-category-edit-form','id' => 'award-category-edit-form','data-table-refresh' => 'award-category-table']) !!}
			@include('award_category._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>