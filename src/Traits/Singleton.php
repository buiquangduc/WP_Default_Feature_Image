<?php 

namespace DTFPT\Traits;

/**
 * Trait for single instance using in this plugin
 *
 * @author Duc Bui Quang <ducbuiquangxd@gmail.com>
 * @since 1.0.0
 */

Trait Singleton
{
	/**
	 * Singleton instance of this class.
	 *
	 * @since   1.0.0
	 * @var     DTFPT\Traits\Singleton
	 */
	protected static $instance = null;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @return void
	 */ 
	protected function __construct() {
		$this->initializes();
	}

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since   1.0.0
	 * @return  DTFPT\Traits\Singleton A single instance of this class.
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * All Initialize actions come here
	 *
	 * @since 1.0.0
	 * @return void
	 */
	abstract public function initializes();
}
	