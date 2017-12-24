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

use Cartalyst\Extensions\ExtensionInterface;
use Cartalyst\Settings\Repository as Settings;
use Illuminate\Contracts\Foundation\Application;
use Cartalyst\Permissions\Container as Permissions;
use Illuminate\Contracts\Routing\Registrar as Router;

return [

    /*
    |--------------------------------------------------------------------------
    | Slug
    |--------------------------------------------------------------------------
    |
    | This is the extension unique identifier and should not be
    | changed as it will be recognized as a new extension.
    |
    | Note:
    |
    |   Ideally this should match the folder structure within the
    |   extensions folder, however this is completely optional.
    |
    */

    'slug' => 'platform/permissions',

    /*
    |--------------------------------------------------------------------------
    | Name
    |--------------------------------------------------------------------------
    |
    | This is the extension name, used mainly for presentational purposes.
    |
    */

    'name' => 'Permissions',

    /*
    |--------------------------------------------------------------------------
    | Description
    |--------------------------------------------------------------------------
    |
    | A brief sentence describing what the extension does.
    |
    */

    'description' => 'Manage your application permissions.',

    /*
    |--------------------------------------------------------------------------
    | Version
    |--------------------------------------------------------------------------
    |
    | This is the extension version and it should be set as a string
    | so it can be used with the version_compare() function.
    |
    */

    'version' => '7.0.0',

    /*
    |--------------------------------------------------------------------------
    | Author
    |--------------------------------------------------------------------------
    |
    | Because everybody deserves credit for their work, right?
    |
    */

    'author' => 'Cartalyst LLC',

    /*
    |--------------------------------------------------------------------------
    | Requirements
    |--------------------------------------------------------------------------
    |
    | Define here all the extensions that this extension depends on to work.
    |
    | Note:
    |
    |   This is used in conjunction with Composer, so you should put the
    |   exact same dependencies on the extension composer.json require
    |   array, so that they get resolved automatically by Composer.
    |
    |   However you can use without Composer, at which point you will
    |   have to ensure that the required extensions are available!
    |
    */

    'requires' => null,

    /*
    |--------------------------------------------------------------------------
    | Service Providers
    |--------------------------------------------------------------------------
    |
    | Define here your extension service providers. They will be dynamically
    | registered without having to include them in config/app.php file.
    |
    */

    'providers' => [

        Platform\Permissions\Providers\PermissionsServiceProvider::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    |
    | Closure that is called when the extension is started. You can register
    | any custom routing logic here.
    |
    | The closure parameters are:
    |
    |   object \Illuminate\Contracts\Routing\Registrar  $router
    |   object \Cartalyst\Extensions\ExtensionInterface  $extension
    |   object \Illuminate\Contracts\Foundation\Application  $app
    |
    */

    'routes' => function (Router $router, ExtensionInterface $extension, Application $app) {

    },

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    |
    | Register here all the permissions that this extension has. These will
    | be shown in the user management area to build a graphical interface
    | where permissions can be selected to allow or deny user access.
    |
    | For detailed instructions on how to register the permissions, please
    | refer to the following url https://cartalyst.com/manual/permissions
    |
    | The closure parameters are:
    |
    |   object \Cartalyst\Permissions\Container  $permissions
    |   object \Illuminate\Contracts\Foundation\Application  $app
    |
    */

    'permissions' => function (Permissions $permissions, Application $app) {
        $permissions->group('global', function ($g) {
            $g->name = trans('platform/permissions::permissions.global');
        });

        $global = $app['config']->get('platform-permissions.global');

        foreach ($global as $permission) {
            foreach ($permission as $key => $label) {
                $permissions->group('global', function($g) use ($key, $label) {
                    $g->permission($key, function ($p) use ($label) {
                        $p->label = $label;
                    });
                });
            }
        }
    },

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | Register here all the settings that this extension has.
    |
    | For detailed instructions on how to register the settings, please
    | refer to the following url https://cartalyst.com/manual/settings
    |
    | The closure parameters are:
    |
    |   object \Cartalyst\Settings\Repository  $settings
    |   object \Illuminate\Contracts\Foundation\Application  $app
    |
    */

    'settings' => function (Settings $settings, Application $app) {

    },

    /*
    |--------------------------------------------------------------------------
    | Menus
    |--------------------------------------------------------------------------
    |
    | You may specify the default various menu hierarchy for your extension.
    |
    | You can provide a recursive array of menu children and their children.
    |
    | These will be created upon installation, synchronized upon upgrading
    | and removed upon uninstallation.
    |
    | Menu children are automatically put at the end of the menu for
    | extensions installed through the Operations extension.
    |
    | The default order (for extensions installed initially) can be
    | found by editing the file "config/platform.php".
    |
    */

    'menus' => [],

];
