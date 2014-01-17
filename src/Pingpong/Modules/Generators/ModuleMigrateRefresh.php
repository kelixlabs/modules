<?php

namespace Pingpong\Modules\Generators;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleMigrateRefresh extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:migrate-refresh';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Reset database and run migration from specified module.';

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
		$database = $this->option('database');

		$name = strtolower($this->argument('name'));
		// $name = ucwords($name);

		$path = \Config::get('modules::module.directory');
		$path.= '/'.$name.'/database/migrations';

		if(!is_dir($path))
		{
			$this->error("Module $name not exists.");
			return;
		}

		$this->info("Migrating from module $name.");

		$parameters = array();
		if(! empty($database))
		{
			$parameters['--database'] = $database;
		}
		$this->call('migrate:reset', $parameters);

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
			array('--database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.', null),
		);
	}

}
