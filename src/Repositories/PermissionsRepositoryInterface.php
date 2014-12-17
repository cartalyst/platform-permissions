<?php namespace Platform\Permissions\Repositories;
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
 * @copyright  (c) 2011-2014, Cartalyst LLC
 * @link       http://cartalyst.com
 */

interface PermissionsRepositoryInterface {

	/**
	 * Sets the permissions inheritance status.
	 *
	 * @param  bool  $status
	 * @return $this
	 */
	public function inheritable($status = true);

	/**
	 * Finds all the registered permissions groups.
	 *
	 * @return array
	 */
	public function findAll();

	/**
	 * Sets permissions from the request.
	 *
	 * @param  string  $inputName
	 * @return $this
	 */
	public function withInput($inputName = 'permissions');

	/**
	 * Prepares the given permissions, merging in any old input.
	 *
	 * @param  array  $permissions
	 * @return array
	 */
	public function prepareEntityPermissions(array $permissions);

	/**
	 * Returns an array with all the permissions
	 * so it's easier to do permission checks.
	 *
	 * @return array
	 */
	public function getPreparedPermissions();

}
