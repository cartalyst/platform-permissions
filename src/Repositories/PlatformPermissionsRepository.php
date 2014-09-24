<?php namespace Platform\Permissions\Repositories;
/**
 * Part of the Platform Permissions extension.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Cartalyst PSL License.
 *
 * This source file is subject to the Cartalyst PSL License that is
 * bundled with this package in the license.txt file.
 *
 * @package    Platform Permissions extension
 * @version    1.0.0
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2014, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Illuminate\Container\Container;

class PlatformPermissionsRepository implements PermissionsRepositoryInterface {

	/**
	 * The Container instance.
	 *
	 * @var \Illuminate\Container\Container
	 */
	protected $app;

	/**
	 * The Platform Permissions Container instance.
	 *
	 * @var \Cartalyst\Permissions\Container
	 */
	protected $permissions;

	/**
	 *
	 *
	 * @var bool
	 */
	protected $inheritable = true;

	/**
	 * Constructor.
	 *
	 * @param  \Illuminate\Container\Container  $app
	 * @return void
	 */
	public function __construct(Container $app)
	{
		$this->app = $app;

		$this->permissions = $app['platform.permissions'];

		$this->preparePermissions();

		$this->registerGlobalPermissions();
	}

	/**
	 * Sets the permissions inheritance status.
	 *
	 * @param  bool  $status
	 * @return $this
	 */
	public function inheritable($status = true)
	{
		$this->inheritable = (bool) $status;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function findAll($removeSuperUser = false)
	{
		// Get all the registered permissions
		$permissions = $this->permissions->sortBy('name')->all();

		//
		foreach ($permissions as $group)
		{
			foreach ($group->all() as $permission)
			{
				// if ($removeSuperUser && $permission->id === 'superuser')
				// {
				// 	unset($group[$permission->id]); continue;
				// }

				$permission->inheritable = $this->inheritable;
			}

			// If the group doesn't have any permissions,
			// we will completely remove the group.
			if (count($group) === 0)
			{
				unset($permissions[$group->id]);
			}
		}

		return $permissions;
	}

	/**
	 * {@inheritDoc}
	 */
	public function prepareEntityPermissions(array $permissions)
	{
		foreach ($permissions as $permission => $access)
		{
			//$value = $this->inheritable ? ($access === true ? 1 : -1) : ($access === true ? 1 : 0);

			$permissions[$permission] = $access;
		}

		return $permissions;
	}

	protected function registerGlobalPermissions()
	{
		$this->permissions->group('1', function($g)
		{
			$g->name = trans('platform/users::permissions.global');

			// Loop through global permissions configuration and attach
			$permissions = $this->app['config']->get('platform/users::permissions.global');

			foreach ($permissions as $permission => $label)
			{
				$g->permission($permission, function($p) use ($label)
				{
					$p->label = $label;
				});
			}
		});
	}

	protected function preparePermissions()
	{
		// Loop through all the enabled extensions
		foreach ($this->app['extensions']->allEnabled() as $extension)
		{
			call_user_func($extension->permissions, $this->permissions);
		}
	}

}
