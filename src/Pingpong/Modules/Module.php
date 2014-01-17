<?php

namespace Pingpong\Modules;

class Module
{
	protected $path;
	protected $ignores = array('.', '..');
	
	public static $instance = null;

	function __construct() {
		$this->path = base_path('modules').'/';
	}

	public static function getInstance()
	{
		if(is_null(static::$instance))
		{
			static::$instance = new self;
		}
		return static::$instance;
	}

	/**
	 * return all modules.
	 *
	 * @return void
	 */
	public function all()
	{
		$modules 	= array();
		$path 		= $this->path;
		
		if( is_dir($path)){		
			$folders = scandir($path);
			foreach ($folders as $folder) {
				if( ! in_array($folder, $this->ignores))
				{
					$modules[] = $folder;
				}	
			}
		}
		return $modules;
	}

	/**
	 * Register all modules.
	 *
	 * @return void
	 */
	public function register()
	{
		$path = $this->path;
		$modules = $this->all();
		if(count($modules) > 0 )
		{
			foreach ($modules as $module) {
				$this->create($module);			

				// including routes
				if(file_exists($route = $path.$module.'/routes.php'))
				{
					require $route;
				}

				// including filters
				if(file_exists($filter = $path.$module.'/filters.php'))
				{
					require $filter;
				}
			}
		}
	}	

	/**
	 * Adding new namespaces for all registered modules.
	 *
	 * @return void
	 */
	public function addNamespaces()
	{		
		$path = $this->path;
		foreach ($this->all() as $key => $name) {

			\Lang::addNamespace($name, $path.$name.'/lang');

		}
	}

	/**
	 * Creating new services, namespaces and others.
	 *
	 * @return void
	 */
	protected function create($module)
	{		
		$path = $this->path;

		\View::addNamespace($module, $path.$module.'/views');

		\Config::addNamespace($module, $path.$module.'/config');

		// create aliases for controllers and models
		$use_alias = \Config::get($module.'::app.alias');
		if($use_alias == TRUE)
		{
			$this->createAliases($module);		
		}

		$modulePath = $path.$module;
		\ClassLoader::addDirectories(array(

			$modulePath.'/commands',
			$modulePath.'/controllers',
			$modulePath.'/models',
			$modulePath.'/database/seeds',

		));
	}

	/**
	 * Creating new aliases.
	 *
	 * @return void
	 */
	protected function createAliases($module)
	{
		$path = $this->path;
		$controllers 	= $path.$module.'/Controllers';	
		$models 		= $path.$module.'/Models';

		$ControllersAliases = $this->getAliases($module, 'Controllers', $controllers);
		$ModelAliases 		= $this->getAliases($module, 'Models', $models);

		// create aliases for controller
		$facades = $ControllersAliases;
		$this->app->booting(function() use ($facades)
		{
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			foreach ($facades as $key => $value) {
				$loader->alias($key, $value);
			}
		});

		// create aliases for models
		$facades = $ModelAliases;
		$this->app->booting(function() use ($facades)
		{
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			foreach ($facades as $key => $value) {
				$loader->alias($key, $value);
			}
		});
	}

	/**
	 * Get all files.
	 *
	 * @return void
	 */
	protected function getAliases($module, $type, $path)
	{
		$ignores = $this->ignores;
		if( ! is_dir($path))
		{
			throw new \Exception("Module path [$path] does not exists!");
		}
		
		$files = array();

		$folders = scandir($path);
		foreach ($folders as $folder) {
			if( ! in_array($folder, $ignores))
			{
				list($name, $ext) = explode(".", $folder);
				$files[$name] = "Modules\\$module\\$type\\$name";
			}	
		}

		return $files;
	}

}