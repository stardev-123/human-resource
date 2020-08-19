		<div style="margin-top: 10px;">
			@foreach($uploads as $upload)
				<p><a href="#" data-ajax="1" data-extra="&id={{$upload->id}}" data-source="/upload-delete" data-refresh="{{$upload->upload_key}}-list" class="click-alert-message"><i class="fa fa-times" style="color: red;margin-right: 10px;"></i></a> {{$upload->user_filename}}</p>
			@endforeach
		</div>
