<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Zabara <wlady2001@gmail.com>
 * Date: 10.03.22
 * Time: 10:15
 * phpcs:ignoreFile
 */

namespace CSV2DB;

use CSV2DB\Controllers;

class Base {
	public $message = null;
	public $controller = null;
	protected $config = null;

	public function __construct( $config ) {
		$this->config = $config;
		// global hooks
		\add_filter( 'plugins_loaded', [ $this, 'on_plugins_loaded' ] );
	}

	/**
	 * Continue setup when all plugins are loaded
	 *
	 * @access public
	 */
	public function on_plugins_loaded() {
		if ( Helper::check_environment() ) {
			$this->init();
		}
	}

	public function init() {
		\load_textdomain( 'csv2db', $this->config['plugin_dir'] . '/src/lang/csv2db-' . \determine_locale() . '.mo' );
		if ( \is_admin() ) {
			$this->controller = new Controllers\Admin( $this->config );
		} else {
			$this->controller = new Controllers\Front( $this->config );
		}
		$this->controller->init();
		if ( isset( $_POST['action'] ) ) {
			$this->controller->dispatch( sanitize_text_field( $_POST['action'] ) );
		}
	}

	public function parse_view( $view, $data = null ) {
		return require( $this->config['plugin_dir'] . '/src/views/' . $view . '.php' );
	}
}

