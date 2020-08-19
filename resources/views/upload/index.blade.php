				<div class="file-uploader" data-module="{{$module}}" data-key="" data-module-id="{{ isset($module_id) ? $module_id : '' }}">
					<div class="file-uploader-button" data-upload-button="{{isset($upload_button) ? $upload_button : trans('messages.upload')}}" data-max-size="{{config('config.max_file_size_upload')*1024*1024}}"></div>
					<span style="margin-top: 5px;" class="file-uploader-list"></span>
					@if(isset($uploads))
						<div style="margin-top: 10px;">
						@foreach($uploads as $upload)
							<p><a href="#" data-ajax="1" data-extra="&id={{$upload->id}}" data-source="/upload-temp-delete" class="click-alert-message mark-hidden"><i class="fa fa-times" style="color: red;margin-right: 10px;"></i></a> {{$upload->user_filename}}</p>
						@endforeach
						</div>
					@endif
				</div>