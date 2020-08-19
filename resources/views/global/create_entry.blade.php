
		@if(Entrust::can('manage-configuration'))
			<div class="help-block pull-right"><a href="#" data-href="/{{$module}}/create" data-toggle="modal" data-target="#myModal" style="text-decoration: none;"><i class="fa fa-plus-circle"></i> {{trans('messages.add_new')}}</a></div>
		@endif