
	<div class="table-responsive">
		<table class="table table-hover datatable" style="width:100%;" data-table-source="{{ $table['source'] }}" data-table-title="{{ $table['title'] }}" id="{{ $table['id'] }}" {!! array_key_exists('form',$table) ? 'data-form="'.$table['form'].'"' : '' !!} {!! array_key_exists('disable-sorting',$table) ? 'data-disable-sorting="'.$table['disable-sorting'].'"' : '' !!}>
			<thead>
				<tr>
					@foreach($table['data'] as $col_head)
					@if($col_head == 'Option')
					<th style="min-width:100px;">{!! $col_head !!}</th>
					@else
					<th>{{ $col_head }}</th>
					@endif
					@endforeach
				</tr>
			</thead>
			<tbody>
			</tbody>
			<tfoot>
			</tfoot>
		</table>
	</div>
