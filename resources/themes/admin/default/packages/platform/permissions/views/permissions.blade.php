<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

	@foreach ($permissions as $group)

	<div class="panel panel-default panel-permissions">

		<div class="panel-heading collapsed" data-toggle="collapse" data-parent="#accordion" data-target="#panel-{{{ $group->id }}}" aria-expanded="true" aria-controls="panel-{{{ $group->id }}}">

			<h4 class="panel-title">
				{{{ $group->name }}}
				<a class="panel-close small pull-right tip" data-original-title="{{{ trans('action.collapse') }}}"></a>
			</h4>

		</div>

		<div id="panel-{{{ $group->id }}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">

			<div class="panel-body">

				@foreach ($group->all() as $permission)
				<div class="row permission">

					<div class="col-sm-3 text-right">
						<label class="control-label">{{{ $permission->label }}}</label>
					</div>

					<div class="col-sm-9">

						<label class="radio-inline" for="{{{ $permission->id }}}_allow">
							<input type="radio" value="1" id="{{{ $permission->id }}}_allow" name="permissions[{{{ $permission->id }}}]"{{ (array_get($entityPermissions, $permission->id) === '1' ? ' checked="checked"' : null) }} data-parsley-ui-enabled="false">
							{{{ trans('common.allow') }}}
						</label>

						<label class="radio-inline" for="{{{ $permission->id }}}_deny">
							<input type="radio" value="-1" id="{{{ $permission->id }}}_deny" name="permissions[{{{ $permission->id }}}]"{{ (array_get($entityPermissions, $permission->id, ($permission->inheritable ? null : '-1')) === '-1' ? ' checked="checked"' : null) }}>
							{{{ trans('common.deny') }}}
						</label>

						@if ($permission->inheritable)
						<label class="radio-inline" for="{{{ $permission->id }}}_inherit">
							<input type="radio" value="0" id="{{{ $permission->id }}}_inherit" name="permissions[{{{ $permission->id }}}]"{{ (array_get($entityPermissions, $permission->id, '0') === '0' ? ' checked="checked"' : null) }}>
							{{{ trans('common.inherit') }}}
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
