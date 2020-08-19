@extends('layouts.app')

	@section('content')

		<div class="row">
			<div class="col-md-6">
				<div class="box-info full">
					<h2><strong>{{$job->title}}</strong></h2>
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th style="width:200px;">{{trans('messages.no_of').' '.trans('messages.post')}}</th>
								<td>{{$job->no_of_post}}</td>
							</tr>
						</thead>
						<tbody>
							@if($job->designation_name)
							<tr>
								<th>{{trans('messages.designation')}}</th>
								<td>{{$job->designation_name}}</td>
							</tr>
							@endif
							@if($job->location_name)
							<tr>
								<th>{{trans('messages.location')}}</th>
								<td>{{$job->location_name}}</td>
							</tr>
							@endif
							<tr>
								<th>{{trans('messages.type')}}</th>
								<td>{{$job->ContractType->name}}</td>
							</tr>
							<tr>
								<th>{{trans('messages.gender')}}</th>
								<td>
									@foreach(explode(',',$job->gender) as $gender)
										{{trans('messages.'.$gender)}} <br />
									@endforeach
								</td>
							</tr>
							@if($job->age_info)
							<tr>
								<th>{{trans('messages.age').' '.trans('messages.range')}}</th>
								<td>{{$job->start_age.' Yr - '.$job->end_age.' Yr'}}</td>
							</tr>
							@endif
							@if($job->salary_info)
							<tr>
								<th>{{trans('messages.salary')}}</th>
								<td>{{currency($job->start_salary,1,$job->currency_id).' - '.currency($job->end_salary,1,$job->currency_id)}}</td>
							</tr>
							@endif
							@if($job->experience)
							<tr>
								<td colspan="2">
									<strong>{{trans('messages.experience')}} :</strong> <br />
									{!!$job->experience!!}
								</td>
							</tr>
							@endif
							@if($job->qualification)
							<tr>
								<td colspan="2">
									<strong>{{trans('messages.qualification')}} :</strong> <br />
									{!!$job->qualification!!}
								</td>
							</tr>
							@endif
							@if($job_uploads->count())
							<tr>
								<td colspan="2">
									<strong>{{trans('messages.attachment')}} :</strong> <br />
									@foreach($job_uploads as $job_upload)
										<p><i class="fa fa-paperclip"></i> <a href="/job/{{$job_upload->uuid}}/download">{{$job_upload->user_filename}}</a></p>
									@endforeach
								</td>
							</tr>
							@endif
							@if($job->description)
							<tr>
								<td colspan="2">
									<strong>{{trans('messages.description')}} :</strong> <br />
									{!!$job->description!!}
								</td>
							</tr>
							@endif
							<tr>
								<th>{{trans('messages.created_at')}}</th>
								<td>{{showDateTime($job->created_at)}}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-6">
				<div class="box-info">
					<h2><strong>{{trans('messages.apply')}}</strong></h2>
					{!! Form::open(['route' => 'job-application.store','role' => 'form', 'class'=>'job-application-form','id' => 'job-application-form','data-file-upload' => '.file-uploader']) !!}
					@include('job_application._form')
					{!! Form::close() !!}
				</div>
			</div>
		</div>

	@endsection