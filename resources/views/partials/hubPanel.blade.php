<div class="user-panel">
	<div class="pull-left image">
		<img src="{{ asset($hub->image) }}" class="img-circle">
	</div>
	<div class="pull-left info">
	<p>{{ $hub->name }}</p>
		@if ($hub->status == 1)
			<a href="javascript:hubDeactivate()" id="hubStatus"><i class="fa fa-circle text-success"></i> <span>Activated</span></a>
		@else
			<a href="javascript:hubReactivate()" id="hubStatus"><i class="fa fa-circle text-danger"></i> <span>Deactivated</span></a>
		@endif
	</div>
</div>
