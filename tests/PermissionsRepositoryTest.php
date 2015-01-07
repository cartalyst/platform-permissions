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
 * @version    1.0.0
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2015, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Mockery as m;
use Cartalyst\Testing\IlluminateTestCase;
use Cartalyst\Permissions\Container as Permissions;
use Platform\Permissions\Repositories\PermissionsRepository;

class PermissionsRepositoryTest extends IlluminateTestCase {

	/**
	 * {@inheritDoc}
	 */
	public function setUp()
	{
		parent::setUp();

		// Additional IoC bindings
		$callback = function() {};

		$this->app['config'] = m::mock('Illuminate\Config\Repository');
		$this->app['config']->shouldReceive('get')
			->andReturn($callback);

		$this->app['extensions'] = m::mock('Cartalyst\Extensions\ExtensionBag');
		$this->app['extensions']->shouldReceive('allEnabled')
			->once()
			->andReturn([$this->extension = m::mock('Cartalyst\Extensions\Extension')]);

		$this->app['translator']->shouldReceive('trans')
			->andReturn('foo');

		$permissions = function(Permissions $permissions)
		{
			$permissions->group('foo', function($g)
			{
				$g->name = 'Foo';

				$g->permission('foo.index', function($p)
				{
					$p->label = trans('platform/foo::permissions.index');

					$p->controller('FooController', 'index');
				});
			});
		};

		$this->extension->shouldReceive('getAttribute')
			->with('permissions')
			->once()
			->andReturn($permissions);

		// Repository
		$this->repository = new PermissionsRepository($this->app);
	}

	/** @test */
	public function it_can_find_and_prepare_permissions()
	{
		$preparedPermissions = [
			'FooController@index' => 'foo.index',
		];

		$permissions = $this->repository->findAll();

		$group = head($permissions);

		$this->assertEquals($preparedPermissions, $this->repository->getPreparedPermissions());

		$this->assertTrue($group->hasPermissions());

		$this->assertArrayHasKey('foo', $permissions);

		$this->assertInstanceOf('Cartalyst\Permissions\Group', $group);
	}

	/** @test */
	public function it_can_prepare_entity_permissions()
	{
		$preparedPermissions = [
			'FooController@index' => 'foo.index',
		];

		$expectedPermissions = [
			'Foo@foo' => 'foo',
			'foo'     => 'bar',
		];

		$this->app['request']->shouldReceive('old')
			->with('permissions', [])
			->once()
			->andReturn(['foo' => 'bar']);

		$this->repository->withInput();

		$this->assertEquals($expectedPermissions, $this->repository->prepareEntityPermissions(['Foo@foo' => 'foo']));
	}

	/** @test */
	public function it_can_set_inheritable()
	{
		// Inheritable false
		$this->repository->inheritable(false);

		$permissions = $this->repository->findAll();

		$group = head($permissions);

		$this->assertFalse($group['foo.index']->get('inheritable'));

		// Inheritable true
		$this->repository->inheritable();

		$permissions = $this->repository->findAll();

		$group = head($permissions);

		$this->assertTrue($group['foo.index']->get('inheritable'));
	}

}
