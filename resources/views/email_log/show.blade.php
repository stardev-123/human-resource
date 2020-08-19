
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title">{!! trans('messages.email').' '.trans('messages.log') !!}</h4>
	</div>
	<div class="modal-body">
		<div class="the-notes info">
			{!! $email->body !!}
		</div>
	</div>
	<div class="modal-footer">
	</div>