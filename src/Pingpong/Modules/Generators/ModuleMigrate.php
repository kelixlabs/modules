<?php

namespace Pingpong\Modules\Generators;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

// use Config;

class ModuleMigrate extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:migrate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Migrate from specified module.';

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
		$name = strtolower($this->argument('name'));

		$path = \Config::get('modules::module.directory');
		$path.= '/'.$name.'/database/migrations';

		$this->info("Migrating from module $name.");

		$this->call('migrate', array(
				'--path'	=>	$path
			)
		);
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
