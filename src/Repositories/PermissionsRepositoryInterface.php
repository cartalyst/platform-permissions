<?php

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
 * @version    7.0.0
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2017, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Platform\Permissions\Repositories;

use Closure;
use Cartalyst\Permissions\Container;

interface PermissionsRepositoryInterface
{
    /**
     * Returns the permissions container.
     *
     * @return \Cartalyst\Permissions\Container
     */
    public function getPermissions();

    /**
     * Sets the permissions container.
     *
     * @return \Cartalyst\Permissions\Container
     */
    public function setPermissions(Container $permissions);

    /**
     * Prepares the given permissions.
     *
     * @param  \Closure  $permissions
     * @return \Cartalyst\Permissions\Container
     */
    public function prepare(Closure $permissions);

    /**
     * Sets the permissions inheritance status.
     *
     * @param  bool  $status
     * @return $this
     */
    public function inheritable($status = true);

    /**
     * Returns the given group permissions.
     *
     * @param  string  $group
     * @return array
     */
    public function find($group);

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
