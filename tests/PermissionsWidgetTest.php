<?php namespace Platform\Permissions\Tests;
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
 * @version    1.0.3
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2015, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Mockery as m;
use Cartalyst\Testing\IlluminateTestCase;
use Cartalyst\Permissions\Container as Permissions;
use Platform\Permissions\Widgets\Permissions as Widget;
use Platform\Permissions\Repositories\PermissionsRepository;

class PermissionsWidgetsTest extends IlluminateTestCase {

	/**
	 * {@inheritDoc}
	 */
	public function setUp()
	{
		parent::setUp();

		$this->app['permissions'] = new Permissions('platform');

		$this->app['extensions'] = m::mock('Cartalyst\Extensions\ExtensionBag');
		$this->app['extensions']
			->shouldReceive('allEnabled')->once()
			->andReturn([ $this->extension = m::mock('Cartalyst\Extensions\Extension') ])
		;

		$permissions = function(Permissions $permissions)
		{
			$permissions->group('foo', function($g)
			{
				$g->name = 'Foo';

				$g->permission('foo.index', function($p)
				{
					$p->label = 'My Permission';

					$p->controller('FooController', 'index');
				});
			});

			$permissions->group('bar');

			$permissions->group('baz');
		};

		$this->extension
			->shouldReceive('getAttribute')
			->with('permissions')->once()
			->andReturn($permissions)
		;

		// Widget
		$this->widget = new Widget(
			new PermissionsRepository($this->app)
		);
	}

	/** @test */
	public function test()
	{
		$this->app['request']->shouldReceive('old')
			->once()
			->andReturn([]);

		$this->app['view']->shouldReceive('make')
			->with('platform/permissions::permissions', m::any(), [])
			->once();

		$this->widget->show();
	}
}
