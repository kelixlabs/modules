<?php namespace Pingpong\Modules;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Application as Application;
use Pingpong\Modules\Module as Module;
use Pingpong\Modules\Generators as Generators;

class ModulesServiceProvider extends ServiceProvider {
	
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;
	
	/**
	 * Array providers.
	 *
	 * @var array
	 */
	protected $providers = array(
	);
	
	/**
	 * Array facades.
	 *
	 * @var array
	 */
	protected $facades = array(
	);

	/**
	 * Booting the service provider.
	 *
	 * @return void
	 */

	public function boot()
	{
		$this->package('pingpong/modules', 'modules');

		$module = Module::getInstance();
		$module->addNamespaces();
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerProviders();
		$this->registerFacades();

		$module = Module::getInstance();
		$module->register();
		
		$this->registerModuleMakeGenerator();
		$this->registerModuleControllerGenerator();
		$this->registerModuleMigrateGenerator();
		$this->registerModuleMigrateMakeGenerator();
		$this->registerModuleMigrateRefreshGenerator();
		$this->registerModuleSeederGenerator();
		$this->registerModuleSetupGenerator();

		$this->commands(
			'module.make',
			'module.controller',
			'module.migrate',
			'module.migrate-make',
			'module.migrate-refresh',
			'module.seeder',
			'module.setup'
		);
	}
	
	/**
	 * Register generators module:setup
	 *
	 * @return Commands\ModelGeneratorCommand
	 */
	protected function registerModuleSetupGenerator()
	{
		$this->app['module.setup'] = $this->app->share(function($app)
		{
			return new Generators\ModuleSetup;
		});
	}
	
	/**
	 * Register generators module:seeder
	 *
	 * @return Commands\ModelGeneratorCommand
	 */
	protected function registerModuleSeederGenerator()
	{
		$this->app['module.seeder'] = $this->app->share(function($app)
		{
			return new Generators\ModuleSeeder;
		});
	}
	
	/**
	 * Register generators module:migrate-refresh
	 *
	 * @return Commands\ModelGeneratorCommand
	 */
	protected function registerModuleMigrateRefreshGenerator()
	{
		$this->app['module.migrate-refresh'] = $this->app->share(function($app)
		{
			return new Generators\ModuleMigrateRefresh;
		});
	}
	
	/**
	 * Register generators module:migrate-make
	 *
	 * @return Commands\ModelGeneratorCommand
	 */
	protected function registerModuleMigrateMakeGenerator()
	{
		$this->app['module.migrate-make'] = $this->app->share(function($app)
		{
			return new Generators\ModuleMigrateMake;
		});
	}
	
	/**
	 * Register generators module:migrate
	 *
	 * @return Commands\ModelGeneratorCommand
	 */
	protected function registerModuleMigrateGenerator()
	{
		$this->app['module.migrate'] = $this->app->share(function($app)
		{
			return new Generators\ModuleMigrate;
		});
	}

	/**
	 * Register generators module:make
	 *
	 * @return Commands\ModelGeneratorCommand
	 */
	protected function registerModuleMakeGenerator()
	{
		$this->app['module.make'] = $this->app->share(function($app)
		{
			return new Generators\ModuleMake;
		});
	}

	/**
	 * Register generators module:controller
	 *
	 * @return Commands\ModelGeneratorCommand
	 */
	protected function registerModuleControllerGenerator()
	{
		$this->app['module.controller'] = $this->app->share(function($app)
		{
			return new Generators\ModuleControllerMake;
		});
	}

	/**
	 * Register all service providers.
	 *
	 * @return void
	 */
	protected function registerProviders()
	{
		$providers = $this->providers;
		foreach ($providers as $key => $value) {
			$this->app[$key] = $this->app->share(function($app) use ($value)
			{
				return new $value;
			});
		}
	}

	/**
	 * Register all facades.
	 *
	 * @return void
	 */
	protected function registerFacades()
	{
		$facades = $this->facades;
		$this->app->booting(function() use ($facades)
		{
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			foreach ($facades as $key => $value) {
				$loader->alias($key, $value);
			}
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		$providers = array();
		foreach ($this->providers as $key => $value) {
			$providers[] = $key;
		}
		return $providers;
	}

}