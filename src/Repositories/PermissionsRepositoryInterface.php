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

interface PermissionsRepositoryInterface {

	/**
	 * Finds all available permissions that are registered.
	 *
	 * @return array
	 */
	public function findAll();

	// /**
	//  * Encodes user permissions to match that of the encoded "all"
	//  * permissions above.
	//  *
	//  * @param  array  $permissions
	//  * @return array
	//  */
	// public function encodePermissions(array $permissions);

	// /**
	//  * Decodes user permissions to match that of the encoded "all"
	//  * permissions above.
	//  *
	//  * @param  array  $permissions
	//  * @return array
	//  */
	// public function decodePermissions(array $permissions);

}
