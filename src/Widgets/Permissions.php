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
 * @version    3.1.0
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2016, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Platform\Permissions\Widgets;

use Platform\Permissions\Repositories\PermissionsRepositoryInterface;

class Permissions
{
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
     * @param  bool  $inheritable
     * @return mixed
     */
    public function show($entityPermissions = [], $inheritable = true)
    {
        $permissions = $this->permissions->inheritable($inheritable)->findAll();

        $entityPermissions = array_map(function ($permission) {
            return is_bool($permission) ? ($permission === true ? '1' : '-1') : '0';
        }, $this->permissions->withInput()->prepareEntityPermissions($entityPermissions));

        return view('platform/permissions::permissions', compact('permissions', 'entityPermissions'));
    }
}
