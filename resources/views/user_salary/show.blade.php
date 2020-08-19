
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! $user_salary->User->full_name.' '.trans('messages.salary') !!}</h4>
	</div>
	<div class="modal-body">
		<div class="table-responsive">
            <table data-sortable class="table table-hover table-striped table-bordered">
                <tbody>
                	<tr>
                		<th>{{trans('messages.from').' '.trans('messages.date')}}</th>
                		<td>{{showDate($user_salary->from_date)}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.to').' '.trans('messages.date')}}</th>
                        <td>{{showDate($user_salary->to_date)}}</td>
                    </tr>
                    @foreach($user_salary->UserSalaryDetail as $user_salary_detail)
                        <tr>
                            <th>
                                {{ $user_salary_detail->SalaryHead->name }}
                                @if($user_salary_detail->SalaryHead->type == 'earning')
                                    <span class="label label-success">{{trans('messages.earning')}}</span>
                                @else
                                    <span class="label label-danger">{{trans('messages.deduction')}}</span>
                                @endif
                            </th>
                            <td>{{currency($user_salary_detail->amount,1,$user_salary->currency_id)}}</td>
                        </tr>
                    @endforeach
                    @if($user_salary->type == 'hourly')
                        <tr>
                            <th>{{trans('messages.hourly_rate')}}</th>
                            <td>{{currency($user_salary->hourly_rate,1,$user_salary->currency_id)}}</td>
                        </tr>
                    @elseif($user_salary->type == 'monthly')
                        <tr>
                            <th>
                                {{trans('messages.overtime').' '.trans('messages.hourly_rate')}}
                                <span class="label label-success">{{trans('messages.earning')}}</span>
                            </th>
                            <td>{{currency($user_salary->overtime_hourly_rate,1,$user_salary->currency_id)}}</td>
                        </tr>
                        <tr>
                            <th>
                                {{trans('messages.late').' '.trans('messages.hourly_rate')}}
                                <span class="label label-danger">{{trans('messages.deduction')}}</span>
                            </th>
                            <td>{{currency($user_salary->late_hourly_rate,1,$user_salary->currency_id)}}</td>
                        </tr>
                        <tr>
                            <th>
                                {{trans('messages.deduction').' '.trans('messages.hourly_rate')}}
                                <span class="label label-danger">{{trans('messages.deduction')}}</span>
                            </th>
                            <td>{{currency($user_salary->deduction_hourly_rate,1,$user_salary->currency_id)}}</td>
                        </tr>
                    @endif
                	<tr>
                		<th>{{trans('messages.description')}}</th>
                		<td>{{$user_salary->description}}</td>
                	</tr>
                	<tr>
                		<th>{{trans('messages.created_at')}}</th>
                		<td>{{showDateTime($user_salary->created_at)}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.updated_at')}}</th>
                        <td>{{showDateTime($user_salary->updated_at)}}</td>
                    </tr>
                    @if(config('config.enable_custom_field'))
                    	@foreach($custom_fields as $custom_field)
                            <tr>
                                <th>{{$custom_field->title}}</th>
                                <td>{!! isset($values[$user_salary->id][$custom_field->id]) ? $values[$user_salary->id][$custom_field->id] : '' !!}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
	</div>