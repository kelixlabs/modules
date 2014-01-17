<?php

namespace Pingpong\Modules\Generators;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleMigrateMake extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:migrate-make';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new migration file for specified module.';

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
		$fields = $this->option('fields');

		$module = strtolower($this->argument('name'));
		// $module = ucwords($module);

		$table = $this->argument('migration');
		$table = strtolower($table);

		$classname = "Create".ucwords($table)."Table";
		$filename = date("Y_m_d_His")."_create_".$table."_table.php";

		$path = \Config::get('modules::module.directory');
		$path.= '/'.$module.'/database/migrations/';

		if(!is_dir($path))
		{
			$this->error("Module $module not exists.");
			return;
		}

		$template = __DIR__.'/templates/migration.txt';
		if(!file_exists($template))
		{
			throw new \Exception("Migration template does not exists!");
		}

		$search = array(
			'{{module}}',		// module name
			'{{classname}}',	// class name
			'{{table}}',		// table name
			'{{fields}}',		// fields
		);

		$replace = array(
			$module,
			$classname,
			$table,
			$this->fetchFields($fields)
		);

		$script = file_get_contents($template); 
		$script = str_replace($search, $replace, $script);
		
		$this->info("Creating migration for module $module.");

		if( ! file_put_contents($path.$filename, $script))
		{
			$this->error("Can not create migration : ".$filename);;
		}else{
			$this->info("Created migration for module $module : ". $filename);
		}
	}

	/**
	 * Fetch --fields as script.
	 *
	 * @return string
	 */
	protected function fetchFields($argument = null)
	{
		$result = '';
		if( ! empty($argument) )
		{
			$fields = str_replace(" ", "", $argument);
			$fields = explode(',', $fields);

			foreach ($fields as $field) {
				$result.= $this->setField($field);
			}
		}
		return $result;
	}

	/**
	 * Set field to script.
	 *
	 * @return string
	 */
	protected function setField($option)
	{
		$result = '';
		if( ! empty($option) )
		{
			$option = explode(":", $option);
			$result.= '			$table->'.$option[1]."('$option[0]')";
			if(count($option) > 0)
			{
				foreach ($option as $key => $o) {
					if($key == 0 OR $key == 1) continue;
					$result.= "->$o()";		
				}
			}
			$result.= ';'.PHP_EOL;
		}
		return $result;
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
			array('migration', InputArgument::REQUIRED, 'Migration name.'),
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
			array('--fields', null, InputOption::VALUE_OPTIONAL, 'Specified fields table.', null),
		);
	}

}
