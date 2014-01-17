<?php

namespace Pingpong\Modules\Generators;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleSetup extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:setup';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Setup module before you create or another module.';

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
		// packagist
		$this->call('config:publish', array('package'	=>	'pingpong/modules'));
				
		$folder = \Config::get('modules::module.path').'/';
		if( is_dir($folder))
		{
			$this->error("Module already setup!");
		}else
		{
			if( ! mkdir($folder, 0775, true))
			{
				$this->error('Can not setup module. Is your root directory writable?');
			}else{
				$this->info('Module setup successfully.');
			}	
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
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
