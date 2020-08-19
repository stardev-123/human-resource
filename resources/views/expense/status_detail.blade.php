
		@if($expense->ExpenseStatusDetail->count())
			@foreach($expense->ExpenseStatusDetail as $expense_status_detail)
				<tr>
					<td>{{$expense_status_detail->Designation->designation_with_department}}</td>
					<td>
						@if($expense_status_detail->status == 'pending')
							<span class="label label-info">{{trans('messages.pending')}}</span>
						@elseif($expense_status_detail->status == 'rejected')
							<span class="label label-danger">{{trans('messages.w_rejected')}}</span>
						@elseif($expense_status_detail->status == 'approved')
							<span class="label label-success">{{trans('messages.w_approved')}}</span>
						@endif
					</td>
					<td>{{$expense_status_detail->remarks}}</td>
					<td>{{($expense_status_detail->status != null && $expense_status_detail->status != 'pending') ? showDateTime($expense_status_detail->updated_at) : ''}}</td>
				</tr>
			@endforeach	
		@else
			<tr>
				<td colspan="5">{{trans('messages.no_data_found')}}</td>
			</tr>
		@endif