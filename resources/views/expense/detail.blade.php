		
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th>{{trans('messages.user')}}</th>
								<td>
									{{$expense->User->name_with_designation_and_department}}
								</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>{{trans('messages.head')}}</th>
								<td>{!! $expense->ExpenseHead->name !!}</td>
							</tr>
							<tr>
								<th>{{trans('messages.status')}}</th>
								<td>{!! $status !!}</td>
							</tr>
							<tr>
								<th>{{trans('messages.date_of').' '.trans('messages.expense')}}</th>
								<td>{{showDate($expense->date_of_expense)}}</td>
							</tr>
							<tr>
								<th>{{trans('messages.amount')}}</th>
								<td>{{currency($expense->amount,1,$expense->Currency->id)}}</td>
							</tr>
							<tr>
								<td colspan="2">
									<strong>{{trans('messages.description')}} : </strong><br />
									{{ $expense->description }}
								</td>
							</tr>
							<tr>
								<td>{{trans('messages.created_at')}} : </td>
								<td>{{ showDateTime($expense->created_at) }}</td>
							</tr>
							<tr>
								<td>{{trans('messages.updated_at')}} : </td>
								<td>{{ showDateTime($expense->updated_at) }}</td>
							</tr>
							<tr>
								<td colspan="2">
								@if($uploads->count())
									<strong>{{trans('messages.attachment')}} : </strong><br />
						            @foreach($uploads as $upload)
						                <p><i class="fa fa-paperclip"></i> <a href="/expense/{{$upload->uuid}}/download">{{$upload->user_filename}}</a></p>
						            @endforeach
						        @endif
						        </td>
							</tr>
						</tbody>
					</table>