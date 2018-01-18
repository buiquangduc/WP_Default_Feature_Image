<?php

namespace WPDFI\Traits;

/**
 * Trait for all classes which have sub-modules
 *
 * @author Duc Bui Quang <ducbuiquangxd@gmail.com>
 * @since 1.0.0
 */

Trait HasModule
{
	/**
	 * Sub-modules of this plugin
	 *
	 * @since 	1.0.0
	 * @var		array
	 */
	protected $modules;

	/**
	 * Load neccessary sub-modules for the this class.
	 * 
	 * @since 1.0.0
	 * @return \WPDFI\Traits\HasModule
	 */
	abstract public function loadModules();

	/**
	 * Magic method to get properties
	 *
	 * @since  1.0.0
	 *
	 * @param  string $name
	 * @return mixed
	 */
	public function __get($name)
	{
		switch($name) {
			// Load sub-module first
			case isset($this->modules[$name]):
				return $this->modules[$name];
				break;
			default:
				throw new \Exception(__("This property does not exists."));
				break;
		}
	}

	/**
	 * Trigger hook actions of all sub-modules
	 *
	 * @since 1.0.0 
	 * @return void
	 */
	public function moduleHooks() {
		foreach($this->modules as $module) {
			if(method_exists($module, 'hooks')) {
				$module->hooks();
			}
		}
	}
	
	/**
	 * Load a sub-module or add a new sub-module to the vatweets().
	 * 
	 * @param	string	$name
	 * @param	mixed	$handle
	 * @since 	1.0.0
	 * @return  mixed $handle
	 */
	public function module($name, $handle = null) {
		if(!isset($this->modules[$name]))
			$this->modules[$name] = $handle;
		return $this->modules[$name];
	}
}