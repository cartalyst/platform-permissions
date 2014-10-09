{{-- Queue assets --}}
{{ Asset::queue('bootstrap.collapse', 'js/bootstrap/collapse.js', 'jquery') }}

<div class="panel-group" id="accordion">

	@foreach ($permissions as $group)
	<div class="panel panel-default">

		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#panel-{{{ $group->id }}}">
					{{{ $group->name }}}
				</a>
			</h4>
		</div>

		<div id="panel-{{{ $group->id }}}" class="panel-collapse collapse">

			<div class="panel-body">

				@foreach ($group->all() as $permission)
				<div class="form-group">

					<label class="col-lg-3 control-label">{{{ $permission->label }}}</label>

					<div class="col-lg-9">

						<label class="radio-inline" for="{{{ $permission->id }}}_allow">
							<input type="radio" value="1" id="{{{ $permission->id }}}_allow" name="permissions[{{{ $permission->id }}}]"{{{ (array_get($entityPermissions, $permission->id) == 1 ? ' checked="checked"' : null) }}}>
							{{{ trans('general.allow') }}}
						</label>

						<label class="radio-inline" for="{{{ $permission->id }}}_deny">
							<input type="radio" value="-1" id="{{{ $permission->id }}}_deny" name="permissions[{{{ $permission->id }}}]"{{{ (array_get($entityPermissions, $permission->id) == -1 ? ' checked="checked"' : null) }}}>
							{{{ trans('general.deny') }}}
						</label>

						@if ($permission->inheritable)
						<label class="radio-inline" for="{{{ $permission->id }}}_inherit">
							<input type="radio" value="0" id="{{{ $permission->id }}}_inherit" name="permissions[{{{ $permission->id }}}]"{{{ ( ! array_get($entityPermissions, $permission->id) ? ' checked="checked"' : null) }}}>
							{{{ trans('general.inherit') }}}
						</label>
						@endif

					</div>

				</div>
				@endforeach

			</div>

		</div>

	</div>
	@endforeach

</div>
