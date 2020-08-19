@extends('layouts.preview')

	@section('content')

        <div class="container">
            <div class="logo-brand header sidebar rows">
                <div class="logo">
                    <h1><a href="/">{{config('config.application_name')}} : <span style="margin-left: 10px;">{{trans('messages.job').' '.trans('messages.opening')}}</span></a></h1>
                </div>
            </div>
            <div class="body content rows">
				<div class="row">
					<div class="col-md-12">
						@if($jobs->count())
						<div class="box-info">
							@foreach($jobs as $job)
							<ul class="media-list search-result">
							  <li class="media">
								<div class="media-body">
								  <h4 class="media-heading"><a href="{{$job->job_url}}">{{$job->title}}</a>

								  <span class="pull-right">{{trans('messages.date_of').' '.trans('messages.closing').' : '.showDate($job->date_of_closing)}}</span>
								  </h4>
								  <span class="label label-warning">{{$job->no_of_post.' '.trans('messages.post')}}</span>
								  @if($job->designation_name)
								  	<span class="label label-info">{{$job->designation_name}}</span>
								  @endif
								  @if($job->location_name)
								  	<span class="label label-success">{{$job->location_name}}</span>
								  @endif

								  <br />
								  <a href="{{$job->job_url}}">{{$job->job_url}}</a>
								  <p>{{ getDesc($job->description,200)  }}</p>
								</div>
							  </li>
							</ul>
							@endforeach
						@else
							@include('global.notification',['message' => trans('messages.no_job_openings'),'type' => 'danger'])
						@endif
						</div>
					</div>
				</div>
			</div>
		</div>

	@endsection