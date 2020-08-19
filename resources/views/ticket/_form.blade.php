		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
				    {!! Form::label('subject',trans('messages.subject'),[])!!}
					{!! Form::input('text','subject',isset($ticket) ? $ticket->subject : '',['class'=>'form-control','placeholder'=>trans('messages.subject')])!!}
			  	</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							{!! Form::label('ticket_category_id',trans('messages.category'),[])!!}
							{!! Form::select('ticket_category_id', $ticket_categories,isset($ticket) ? $ticket->ticket_category_id : '',['class'=>'form-control show-tick','title' => trans('messages.select_one')])!!}
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							{!! Form::label('ticket_priority_id',trans('messages.priority'),[])!!}
							{!! Form::select('ticket_priority_id', $ticket_priorities,isset($ticket) ? $ticket->ticket_priority_id : '',['class'=>'form-control show-tick','title' => trans('messages.select_one')])!!}
						</div>
					</div>
				</div>
				@include('upload.index',['module' => 'ticket','upload_button' => trans('messages.upload').' '.trans('messages.file'),'module_id' => isset($ticket) ? $ticket->id : ''])
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('description',trans('messages.description'),[])!!}
					{!! Form::textarea('description',isset($ticket->description) ? $ticket->description : '',['size' => '30x15', 'class' => 'form-control summernote', 'placeholder' => trans('messages.description'),'data-height' => 100])!!}
				</div>
			</div>
		</div>
		{{ getCustomFields('ticket-form',$custom_field_values) }}
		{!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}