
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">Forward</h4>
	</div>
	<div class="modal-body">
		<div class="row">
			<div class="col-md-12">
				{!! Form::model($message,['files'=>'true','method' => 'POST','route' => ['message.post-forward',$message->uuid] ,'class' => 'forward-form','id' => 'forward-form','data-file-upload' => '.file-uploader']) !!}
					<div class="form-group">
						{!! Form::select('to_user_id', $users, '',['class'=>'form-control show-tick','placeholder'=>trans('messages.select_one')])!!}
					</div>
					<div class="form-group">
						{!! Form::input('text','subject','Fw: '.$message->subject,['class'=>'form-control','placeholder'=>trans('messages.subject')])!!}
					</div>
					<div class="form-group">
						{!! Form::textarea('body',$message->body,['class' => 'form-control summernote', 'placeholder' => trans('messages.body')])!!}
					</div>
					@include('upload.index',['module' => 'message','upload_button' => trans('messages.upload').' '.trans('messages.file'),'module_id' => $message->id])
					<div class="form-group">
						<button type="submit" class="btn btn-success btn-sm pull-right"><i class="fa fa-paper-plane"></i> {!! trans('messages.send') !!}</button>
					</div>	
				{!! Form::close() !!}
			</div>
		</div>
	</div>