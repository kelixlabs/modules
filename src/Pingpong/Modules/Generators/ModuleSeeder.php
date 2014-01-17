<?php

namespace Pingpong\Modules\Generators;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleSeeder extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:db-seed';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Seed from specified module.';

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
		$name = $module = $this->argument('name');
		$name = ucwords($name);

		$path = \Config::get('modules::module.directory');
		$path.= '/'.$module.'/database/seeds/';

		if(!is_dir($path))
		{
			$this->error("Module $module not exists.");
			return;
		}

		$class = $name . 'DatabaseSeeder';

		if(class_exists($class))
		{
			$this->info("Seeding from module $name.");

			$this->call('db:seed', array(
					'--class'	=>	$class
				)
			);	
		}else
		{
			$this->error("Class 'DatabaseSeeder' on module [$name] not exists.");
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
			array('name', InputArgument::REQUIRED, 'Module name.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
		);
	}

}
