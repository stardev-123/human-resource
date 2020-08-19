                  	<p class="text-center"><strong>{{$task->title}}</strong>
                  		<a href="#" data-ajax="1" data-extra="&task_id={{$task->id}}" data-source="/task-starred" style="color:black;" data-refresh="load-task-detail" > 
	                  		@if($task->StarredTask->where('user_id',Auth::user()->id)->count())
	                  			<i class="fa fa-star fa-2x starred" data-toggle="tooltip" title="{{trans('messages.remove').' '.trans('messages.favourite')}}"></i>
	                  		@else
	                  			<i class="fa fa-star-o fa-2x" data-toggle="tooltip" title="{{trans('messages.mark').' '.trans('messages.as').' '.trans('messages.favourite')}}"></i>
	                  		@endif
                  		</a>
                  	</p>
                  	<p class="text-center" style="margin:20px 0;">{!! $status !!}</p>
                   	<div class="table-responsive">
	                    <table class="table table-stripped table-hover show-table">
	                        <tbody>
	                            <tr>
	                                <th>{{trans('messages.owner')}}</th>
	                                <td>{{$task->UserAdded->name_with_designation_and_department}}</td>
	                            </tr>
	                            <tr>
	                                <th>{{trans('messages.category')}}</th>
	                                <td>{{$task->TaskCategory->name}}</td>
	                            </tr>
	                            <tr>
	                                <th>{{trans('messages.priority')}}</th>
	                                <td>{{$task->TaskPriority->name}}</td>
	                            </tr>
	                            <tr>
	                                <th>{{trans('messages.start').' '.trans('messages.date')}}</th>
	                                <td>{{showDate($task->start_date)}}</td>
	                            </tr>
	                            <tr>
	                                <th>{{trans('messages.due').' '.trans('messages.date')}}</th>
	                                <td>{{showDate($task->due_date)}}</td>
	                            </tr>
	                            <tr>
	                                <th>{{trans('messages.complete').' '.trans('messages.date')}}</th>
	                                <td>{{showDateTime($task->complete_date)}}</td>
	                            </tr>
	                            <tr>
	                                <th>{{trans('messages.progress')}}</th>
	                                <td>
	                                	{{$task->progress}}% <div class="progress progress-xs" style="margin-top:0px;">
										<div class="progress-bar progress-bar-{{progressColor($task->progress)}}" role="progressbar" aria-valuenow="{{$task->progress}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$task->progress}}%">
										  </div>
										</div>
	                                </td>
	                            </tr>
	                            <tr>
	                            	<th>{{trans('messages.tags')}}</th>
	                            	<td>
	                            		@foreach(explode(',',$task->tags) as $tag)
	                            			<span class="label label-info">{{$tag}}</span> 
	                            		@endforeach
	                            	</td>
	                            </tr>
	                        </tbody>
	                    </table>
	                </div>