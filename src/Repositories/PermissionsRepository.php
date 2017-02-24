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
 * @version    5.0.0
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2017, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Platform\Permissions\Repositories;

use Closure;
use Illuminate\Container\Container;
use Cartalyst\Permissions\Container as Permissions;

class PermissionsRepository implements PermissionsRepositoryInterface
{
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
     * The Sentinel instance.
     *
     * @var \Cartalyst\Sentinel\Sentinel
     */
    protected $sentinel;

    /**
     * The permissions inheritance status.
     *
     * @var bool
     */
    protected $inheritable = true;

    /**
     * Input array
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

        $this->permissions = $app['permissions'];

        $this->sentinel = $app['sentinel'];

        $this->preparePermissions();
    }

    /**
     * {@inheritDoc}
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * {@inheritDoc}
     */
    public function setPermissions(Permissions $permissions)
    {
        $this->permissions = $permissions;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function prepare(Closure $permissions)
    {
        $container = new Permissions('platform');

        call_user_func($permissions, $container, $this->app);

        return $container;
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
    public function find($group)
    {
        return $this->permissions->find($group);
    }

    /**
     * {@inheritDoc}
     */
    public function findAll()
    {
        // Get all the registered permissions
        $groups = $this->permissions->sortBy('name')->makeFirst('global')->all();

        // Loop through the groups
        foreach ($groups as $group) {
            // If the group doesn't have permissions,
            // we will completely remove the group.
            if (count($group) === 0) {
                unset($groups[$group->id]);

                continue;
            }

            // Loop through the group permissions
            foreach ($group->all() as $permission) {
                $permission->inheritable = $this->inheritable;

                if ($this->sentinel->hasAccess('permissions') && $permission->id !== 'superuser') {
                    continue;
                }

                if (! $this->sentinel->hasAnyAccess(['superuser', $permission->id])) {
                    unset($groups[$group->id][$permission->id]);
                }
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
        foreach ($permissions as $permission => $access) {
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

        foreach ($this->findAll() as $group) {
            foreach ($group->all() as $permission) {
                if ($permission->controller) {
                    foreach ($permission->methods as $method) {
                        $permissions["{$permission->controller}@{$method}"] = $permission->id;
                    }
                }
            }
        }

        return $permissions;
    }

    /**
     * Prepares permissions.
     *
     * @return void
     */
    protected function preparePermissions()
    {
        // Loop through all the enabled extensions
        foreach ($this->app['extensions.bag']->allEnabled() as $extension) {
            $callable = $extension->permissions;

            if ($callable instanceof Closure) {
                call_user_func($callable, $this->permissions, $this->app);
            }
        }
    }
}
