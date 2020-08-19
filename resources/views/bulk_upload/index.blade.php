@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! trans('messages.'.$module).' '.trans('messages.upload') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-md-5">
				<div class="box-info">
					<h2><strong>{{trans('messages.select').' '.trans('messages.column')}}</strong></h2>
					{!! Form::open(['route' => $module.'.bulk-upload','role' => 'form', 'class'=>'form-horizontal bulk-upload-form','id' => 'bulk-upload-form']) !!}
						@foreach($columns as $key => $column)
						<div class="form-group">
						    {!! Form::label($key,trans('messages.column').' '.ucfirst($key),['class' => 'col-sm-4 control-label'])!!}
						    <div class="col-sm-8">
								{!! Form::select($key, $column_options,$column,['class'=>'form-control show-tick','title' => trans('messages.select_one')])!!}
							</div>
						</div>
						@endforeach
						{!! Form::submit(trans('messages.upload'),['class' => 'btn btn-primary pull-right']) !!}
					{!! Form::close() !!}
				</div>
			</div>
			<div class="col-md-7">
				<div class="box-info">
					<h2><strong>Uploaded File Preview (Max 5 rows)</strong></h2>
					<div class="table-responsive">
						<table data-sortable class="table table-hover table-striped table-bordered">
							<thead>
								<tr>
									@foreach($columns as $key => $value)
										<th>{{trans('messages.column').' '.ucfirst($key)}}</th>
									@endforeach
								</tr>
							</thead>
							<tbody>
								@foreach($xls_datas as $xls_data)
									<tr>
										@foreach($columns as $key => $value)
											<td>{{$xls_data[$key]}}</td>
										@endforeach
									</tr>
								@endforeach
								<tr>
									<td colspan="{{count($columns)}}"> ....</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	@stop