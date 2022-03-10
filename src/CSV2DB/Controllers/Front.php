<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Zabara <wlady2001@gmail.com>
 * Date: 10.03.22
 * Time: 10:15
 */

namespace CSV2DB\Controllers;

use CSV2DB\Options;

class Front extends Options {

	public function __construct( $config ) {
		parent::__construct( $config );

		\register_activation_hook( $this->plugin_file, array( $this, 'init' ) );

	}

	public function dispatch( $action ) {
	}

	public function init() {
	}
}
