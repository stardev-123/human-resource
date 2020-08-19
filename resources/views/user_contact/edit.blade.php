
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.contact') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($user_contact,['method' => 'PATCH','route' => ['user-contact.update',$user_contact->id] ,'class' => 'user-contact-edit-form', 'id' => 'user-contact-edit-form','data-table-refresh' => 'user-contact-table']) !!}
		  	@include('user_contact._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
	</div>
