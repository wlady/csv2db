<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Zabara <wlady2001@gmail.com>
 * Date: 10.03.22
 * Time: 10:15
 */

namespace CSV2DB;

class Options extends Base {
	const OPTIONS_NAME = 'csv2db';

	protected $config = null;

	protected $options = null;

	public function __construct( $config ) {
		parent::__construct( $config );
		$this->config = $config;
		if ( ! ( $this->options = \get_option( self::OPTIONS_NAME ) ) ) {
			$this->options = $this->defaults();
			\add_option( 'csv2db', $this->options );
		}
	}

	/**
	 * Return the default options
	 *
	 * @return array
	 */
	protected function defaults() {
		return [
			'use-local'         => 1,
			'fields-terminated' => ',',
			'fields-enclosed'   => '"',
			'fields-escaped'    => '\\\\',
			'lines-starting'    => '',
			'lines-terminated'  => '\\n',
			'fields'            => [],
		];
	}

	/**
	 * Return the empty field
	 *
	 * @return array
	 */
	protected function generate_empty_field( $field_name ) {
		return [
			'name'  => $field_name,
			'type'  => 'VARCHAR',
			'size'  => 255,
			'null'  => 0,
			'ai'    => 0,
			'index' => '',
			'title' => '',
			'show'  => 0,
			'align' => '',
			'check' => 0,
		];
	}

	/**
	 * Called on uninstall
	 * @throws \Exception
	 */
	public static function purge_options() {
		\delete_option( self::OPTIONS_NAME );
		\delete_site_option( self::OPTIONS_NAME );
	}

	/**
	 * Get specific option from the options table
	 *
	 * @param string $option Name of option to be used as array key for retrieving the specific value
	 *
	 * @return mixed
	 */
	public function get_option( $option, $options = null ) {
		if ( is_null( $options ) ) {
			$options = $this->options;
		}
		if ( isset ( $options[ $option ] ) ) {
			return $options[ $option ];
		} else {
			return false;
		}
	}

	/**
	 * Update the options in the options table from the POST
	 *
	 * @param mixed $options
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function update( $options ) {
		if ( ! check_admin_referer( 'csv2db-options' ) ) {
			throw new \Exception( __( 'Operation is not permitted', 'csv2db' ) );
		}
		if ( isset( $_POST['csv2db-defaults'] ) ) {
			$this->options = $this->defaults();
		} else {
			$this->options = $options;
		}

		return $this->options;
	}
}
