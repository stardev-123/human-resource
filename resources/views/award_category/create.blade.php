	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.add_new').' '.trans('messages.award').' '.trans('messages.category') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::open(['route' => 'award-category.store','role' => 'form', 'class'=>'award-category-form','id' => 'award-category-form']) !!}
			@include('award_category._form')
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>