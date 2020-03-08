<?php

/*
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
 * @version    9.0.0
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2020, Cartalyst LLC
 * @link       https://cartalyst.com
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Global Permissions
    |--------------------------------------------------------------------------
    |
    | The array below is any global permissions which you may specify for your
    | application, which are not declared in extensions. We provide an easy way
    | to add permissions to your user management here.
    |
    | When writing permissions, if you put a 'key' => 'value' pair, the 'value'
    | will be the label for the permission which is displayed when editing
    | permissions.
    |
    */

    'global' => [
        [
            'superuser' => 'Superuser',
        ],

        [
            'permissions' => 'Edit Permissions',
        ],
    ],
];
