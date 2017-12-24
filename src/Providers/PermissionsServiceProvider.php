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

namespace Platform\Permissions\Providers;

use Cartalyst\Permissions\Container;
use Cartalyst\Support\ServiceProvider;

class PermissionsServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function boot()
    {
        // Register the Blade @permissions extension
        $this->registerBladePermissionsWidget();
    }

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $this->prepareResources();

        $this->bindIf('permissions', function ($app) {
            return new Container('platform');
        });

        $this->bindIf('platform.permissions', 'Platform\Permissions\Repositories\PermissionsRepository');
    }

    /**
     * Prepare the package resources.
     *
     * @return void
     */
    protected function prepareResources()
    {
        $config = realpath(__DIR__.'/../../resources/config/config.php');

        $this->mergeConfigFrom($config, 'platform-permissions');

        $this->publishes([
            $config => config_path('platform-permissions.php'),
        ], 'config');
    }

    /**
     * Register the Blade @permissions extension.
     *
     * @return void
     */
    protected function registerBladePermissionsWidget()
    {
        $this->app['blade.compiler']->directive('permissions', function ($value) {
            return "<?php echo Widget::make('platform/permissions::permissions.show', array($value)); ?>";
        });
    }
}
