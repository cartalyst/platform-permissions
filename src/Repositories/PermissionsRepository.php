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

use Closure;
use Illuminate\Container\Container;
use Cartalyst\Permissions\Container as Permissions;

class PermissionsRepository implements PermissionsRepositoryInterface {

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
	 * The permissions inheritance status.
	 *
	 * @var bool
	 */
	protected $inheritable = true;

	/**
	 *
	 *
	 * @var array
	 */
	protected $input = [];

	/**
	 * Constructor.
	 *
	 * @param  \Illuminate\Container\Container  $app
	 * @return void
	 */
	public function __construct(Container $app)
	{
		$this->app = $app;

		$this->preparePermissions();
	}

	/**
	 * {@inheritDoc}
	 */
	public function inheritable($status = true)
	{
		$this->inheritable = (bool) $status;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function findAll()
	{
		// Get all the registered permissions
		$groups = $this->permissions->sortBy('name')->all();

		// Loop through the groups
		foreach ($groups as $group)
		{
			// If the group doesn't have permissions,
			// we will completely remove the group.
			if (count($group) === 0)
			{
				unset($groups[$group->id]);

				continue;
			}

			// Loop through the group permissions
			foreach ($group->all() as $permission)
			{
				$permission->inheritable = $this->inheritable;
			}
		}

		return $groups;
	}

	/**
	 * {@inheritDoc}
	 */
	public function withInput($inputName = 'permissions')
	{
		$this->input = $this->app['request']->old($inputName, []);

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function prepareEntityPermissions(array $permissions)
	{
		// Prepare the given entity permissions
		foreach ($permissions as $permission => $access)
		{
			$permissions[$permission] = $access;
		}

		// Return the prepared permissions
		return array_merge($permissions, $this->input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getPreparedPermissions()
	{
		$permissions = [];

		foreach ($this->findAll() as $group)
		{
			foreach ($group->all() as $permission)
			{
				if ($permission->controller)
				{
					foreach ($permission->methods as $method)
					{
						$permissions["{$permission->controller}@{$method}"] = $permission->id;
					}
				}
			}
		}

		return $permissions;
	}


	protected function preparePermissions()
	{
		$this->permissions = new Permissions('platform');

		// Loop through all the enabled extensions
		foreach ($this->app['extensions']->allEnabled() as $extension)
		{
			$callable = $extension->permissions;

			if ($callable instanceof Closure)
			{
				call_user_func($callable, $this->permissions);
			}
		}

		call_user_func(
			$this->app['config']->get('platform/permissions::global'),
			$this->permissions->group('1', function($g)
			{
				$g->name = trans('platform/permissions::permissions.global');
			})
		);
	}

}
