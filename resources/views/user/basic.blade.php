                    <div class="table-responsive">
                        <table class="table table-stripped table-hover show-table">
                            <thead>
                            	<tr>
	                            	<th>{{trans('messages.role')}}</th>
	                            	<th>
	                            		<ol>
	                            		@foreach($user->roles as $role)
	                            			<li>{{$role->name}}</li>
	                            		@endforeach
	                            		</ol>
	                            	</th>
	                            	<th>{{trans('messages.user').' '.trans('messages.code')}}</th>
	                            	<th>{{ $user->Profile->employee_code }}</th>
	                            </tr>
                            </thead>
                            <tbody>
                            	<tr>
	                            	<th>{{trans('messages.name')}}</th>
	                            	<td>{{$user->full_name}}</td>
	                            	<th>{{trans('messages.gender')}}</th>
	                            	<td>{{($user->Profile->gender) ? trans('messages.'.$user->Profile->gender) : ''}}</td>
	                            </tr>
                            	<tr>
	                            	<th>{{trans('messages.unique_identification_number')}}</th>
	                            	<td>{{$user->Profile->unique_identification_number}}</td>
	                            	<th>{{trans('messages.marital').' '.trans('messages.status')}}</th>
	                            	<td>{{$user->Profile->marital_status}}</td>
	                            </tr>
                            	<tr>
	                            	<th>{{trans('messages.nationality')}}</th>
	                            	<td>{{$user->Profile->nationality}}</td>
	                            	<th>{{trans('messages.phone')}}</th>
	                            	<td>{{$user->Profile->phone}}</td>
	                            </tr>
                            	<tr>
	                            	<th>{{trans('messages.date_of').' '.trans('messages.birth')}}</th>
	                            	<td>{{showDate($user->Profile->date_of_birth)}}</td>
	                            	<th>{{trans('messages.date_of').' '.trans('messages.anniversary')}}</th>
	                            	<td>{{showDate($user->Profile->date_of_anniversary)}}</td>
	                            </tr>
                            	<tr>
	                            	<th>{{trans('messages.address')}}</th>
	                            	<td>{{ $user->Profile->address_line_1.' '.$user->Profile->address_line_2}}</td>
	                            	<th>{{trans('messages.city')}}</th>
	                            	<td>{{$user->Profile->city}}</td>
	                            </tr>
                            	<tr>
	                            	<th>{{trans('messages.state')}}</th>
	                            	<td>{{$user->Profile->state}}</td>
	                            	<th>{{trans('messages.postcode')}}</th>
	                            	<td>{{$user->Profile->zipcode}}</td>
                            	</tr>
                            	<tr>
	                            	<th>{{trans('messages.country')}}</th>
	                            	<td colspan="3">{{($user->Profile->country_id) ? config('country.'.$user->Profile->country_id) : ''}}</td>
                            	</tr>
                            </tbody>
                        </table>
                    </div>
