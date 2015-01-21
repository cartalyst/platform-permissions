<?php namespace Platform\Permissions\Widgets;
/**
 * Part of the Platform Permissions extension.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Cartalyst PSL License.
 *
 * This source file is subject to the Cartalyst PSL License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Platform Permissions extension
 * @version    1.0.0
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2015, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Platform\Permissions\Repositories\PermissionsRepositoryInterface;

class Permissions {

	/**
	 * The Permissions repository.
	 *
	 * @var \Platform\Permissions\Repositories\PermissionsRepositoryInterface
	 */
	protected $permissions;

	/**
	 * Constructor.
	 *
	 * @param  \Platform\Permissions\Repositories\PermissionsRepositoryInterface  $permissions
	 * @return void
	 */
	public function __construct(PermissionsRepositoryInterface $permissions)
	{
		$this->permissions = $permissions;
	}

	/**
	 * Shows the available permissions.
	 *
	 * @param  array  $entityPermissions
	 * @return mixed
	 */
	public function show(array $entityPermissions = [])
	{
		$permissions = $this->permissions->inheritable()->findAll();

		$entityPermissions = $this->permissions->withInput()->prepareEntityPermissions($entityPermissions);

		$entityPermissions = [];

		return view('platform/permissions::permissions', compact('permissions', ' entityPermissions'));
	}

}
