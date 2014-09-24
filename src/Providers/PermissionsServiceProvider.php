<?php namespace Platform\Permissions\Providers;
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

use Illuminate\Support\ServiceProvider;
use Cartalyst\Permissions\Container as Permissions;

class PermissionsServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		$this->package('platform/permissions', 'platform/permissions', __DIR__.'/../..');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		$this->app['platform.permissions'] = $this->app->share(function($app)
		{
			return new Permissions('platform');
		});

		$this->registerPermissionsFilter();

		$this->app->bindIf(
			'Platform\Permissions\Repositories\PermissionsRepositoryInterface',
			'Platform\Permissions\Repositories\PlatformPermissionsRepository'
		);
	}

	protected function registerPermissionsFilter()
	{
		if ( ! $this->app['events']->hasListeners('router.filter: permissions'))
		{
			$this->app['router']->filter('permissions', function($route, $request)
			{
				$sentinel = $this->app['sentinel'];

				$action = $route->getActionName();

				if ($sentinel->hasAnyAccess(['superuser', $action])) return;

				//$perms = $this->app['Platform\Permissions\Repositories\PermissionsRepositoryInterface']->findAll();

				// $permissions = [];

				// foreach ($this->app['extensions']->allEnabled() as $extension)
				// {
				// 	$_permissions = value($extension->permissions);

				// 	if ($_permissions)
				// 	{
				// 		$permissions = array_merge($permissions, $_permissions);
				// 	}
				// }

				// if ($action = array_get($permissions, $action, null))
				// {
				// 	$message = Lang::get('platform/foundation::permissions.no_access_to', compact('action'));
				// }
				// else
				// {
				// 	$message = Lang::get('platform/foundation::permissions.no_access');
				// }

				// return Redirect::to('/')->withErrors($message);
			});
		}
	}

}
