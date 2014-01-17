<?php

namespace Pingpong\Modules\Generators;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleControllerMake extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:controller-make';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new restful controller for specified module.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$module 	= strtolower($this->argument('module'));
		$controller = $this->argument('controller');

		$path = \Config::get('modules::module.path').'/'.$module.'/controllers/';
		if(!is_dir($path))
		{
			$this->error("Module $module not exists.");
			return;
		}
		$template = file_get_contents(__DIR__.'/templates/controller.txt');
		$search = array(
			'{{controller}}',
			'{{module}}'	
		);
		$replace = array(
			ucwords($controller),
			ucwords($module),
		);

		$controllerName = $path . ucwords($controller).'Controller.php';

		// script
		$script = str_replace($search, $replace, $template);

		if( ! file_put_contents($controllerName, $script))
		{
			$this->error('Can not create controller!');
		}
		else{
			$this->info('Controller created successfully.');
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('module', InputArgument::REQUIRED, 'Module name.'),
			array('controller', InputArgument::REQUIRED, 'Controller name.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}
