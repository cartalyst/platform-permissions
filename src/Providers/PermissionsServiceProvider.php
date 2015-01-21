<?php namespace Platform\Permissions\Providers;
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

use Cartalyst\Support\ServiceProvider;

class PermissionsServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the extension component namespaces
		$this->package('platform/permissions', 'platform/permissions', __DIR__.'/../..');

		// Register the Blade @permissions extension
		$this->registerBladePermissionsWidget();
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		$this->bindIf('platform.permissions', 'Platform\Permissions\Repositories\PermissionsRepository');
	}

	/**
	 * Register the Blade @permissions extension.
	 *
	 * @return void
	 */
	protected function registerBladePermissionsWidget()
	{
		$this->app['blade.compiler']->extend(function($value)
		{
			$matcher = '/(\s*)@permissions(\(.*?\)\s*)/';

			return preg_replace($matcher, '<?php echo Widget::make("platform/permissions::permissions.show", array$2); ?>', $value);
		});
	}

}
