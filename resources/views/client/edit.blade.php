<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
	<h4 class="modal-title">{!! trans('messages.view').' '.trans('messages.client') !!}</h4>
</div>
<div class="modal-body">
					{!! Form::model($client,['method' => 'PATCH','route' => ['client.update',$client] ,'class' => 'client-edit-form','id' => 'client-edit-form','data-file-upload' => '.file-uploader','data-refresh' => 'table']) !!}

					@include('client._form', ['buttonText' => trans('messages.update')])

					{!! Form::close() !!}

 <div class="clear"></div>
</div>
