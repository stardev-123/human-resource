
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.award') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($award,['method' => 'PATCH','route' => ['award.update',$award] ,'class' => 'award-edit-form','id' => 'award-edit-form','data-file-upload' => '.file-uploader']) !!}
			@include('award._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>