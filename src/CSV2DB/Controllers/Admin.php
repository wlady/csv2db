<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Zabara <wlady2001@gmail.com>
 * Date: 10.03.22
 * Time: 10:15
 */

namespace CSV2DB\Controllers;

use CSV2DB\Models\File;
use CSV2DB\Models\Table;
use CSV2DB\Options;

class Admin extends Options {
	protected $upload_max_filesize = 0;
	// used in Bootstrap Table
	protected $data_id_field = 'id';
	// Every POST action has related method
	private $actions = [
		'save_fields', // save_fields_action etc
		'export_schema',
		'create_table',
		'clear_fields',
		'export_fields',
		'import_fields',
	];
	// Every hook has related method
	// admin_init_hook => [10, 1] by default
	private $hooks = [
		'admin_init'          => [],
		'admin_menu'          => [],
		'wp_ajax_import_csv'  => [],
		'wp_ajax_analyze_csv' => [],
		'wp_ajax_get_items'   => [],
	];
	// Styles to enqueue (related to plugin directory)
	private $styles = [
		'/assets/bootstrap/css/bootstrap.min.css',
		'/assets/bootstrap-icons/bootstrap-icons.css',
		'/assets/bootstrap-table/bootstrap-table.css',
		'/assets/style.css',
	];
	// Scripts to enqueue (related to plugin directory)
	private $scripts = [
		'/assets/popper.min.js',
		'/assets/bootstrap/js/bootstrap.min.js',
		'/assets/tableexport.jquery.plugin/tableExport.min.js',
		'/assets/tableexport.jquery.plugin/libs/jsPDF/jspdf.umd.min.js',
		'/assets/bootstrap-table/bootstrap-table.js',
		'/assets/bootstrap-table/extensions/export/bootstrap-table-export.min.js',
		'/assets/utilities.js',
	];

	public function __construct( $config ) {
		parent::__construct( $config );
		$this->upload_max_filesize = File::convert_bytes( ini_get( 'upload_max_filesize' ) );
	}

	public function init() {
		// settings link on Installed Plugins page must contain filename in filter name
		\add_filter( 'plugin_action_links_' . CSV2DB_PLUGIN_NAME, [ __CLASS__, 'plugin_action_links' ] );
		// initialize hooks
		foreach ( $this->hooks as $hook => $params ) {
			$method = $hook . '_hook';
			if ( method_exists( $this, $method ) ) {
				\add_filter( $hook, [ $this, $method ], $params[0] ?? 10, $params[1] ?? 1 );
			}
		}
		// enqueue styles
		foreach ( $this->styles as $style ) {
			\wp_enqueue_style(
				md5( $style ), \plugins_url( $style, $this->config['plugin_basename'] ),
				[],
				CSV2DB_VERSION
			);
		}
		// enqueue scripts
		foreach ( $this->scripts as $script ) {
			\wp_enqueue_script(
				md5( $script ), \plugins_url( $script, $this->config['plugin_basename'] ),
				[],
				CSV2DB_VERSION
			);
		}
	}

	/**
	 * @param $action
	 *
	 * @throws \Exception
	 */
	public function dispatch( $action ) {
		// route POST requests
		if ( in_array( $action, $this->actions ) ) {
			$method = $action . '_action';
			if ( method_exists( $this, $method ) ) {
				$this->$method();
			} else {
				throw new \Exception( __( 'Method ' . $method . ' was not found', 'csv2db' ) );
			}
		}
	}

	/**
	 * @Hook admin_init
	 */
	public function admin_init_hook() {
		\register_setting( 'csv2db', 'csv2db', [ $this, 'update' ] );
		\wp_enqueue_script( 'jquery' );
		\wp_localize_script( 'jquery', 'ajax', [
			'url'   => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'csv2db_ajax_nonce' ),
		] );
	}

	/**
	 * Add Settings link
	 *
	 * @param $links
	 *
	 * @return array
	 */
	public static function plugin_action_links( $links ) {
		$action_links = [
			'settings' => '<a href="' . admin_url( 'admin.php?page=wp-csv-to-db-settings' ) . '" aria-label="' . esc_attr__( 'Settings',
					'csv2db' ) . '">' . esc_html__( 'Settings', 'csv2db' ) . '</a>',
		];

		return array_merge( $links, $action_links );
	}

	/**
	 * Add options page
	 *
	 * @Hook admin_menu
	 */
	public function admin_menu_hook() {
		if ( \current_user_can( 'manage_options' ) ) {
			\add_menu_page(
				__( 'CSV To DB', 'csv2db' ),
				__( 'CSV To DB', 'csv2db' ),
				'manage_options',
				'wp-csv-to-db', [
				$this,
				'items_page_action',
			], 'dashicons-book-alt' );
			\add_submenu_page(
				'wp-csv-to-db',
				__( 'Import', 'csv2db' ),
				__( 'Import', 'csv2db' ),
				'manage_options',
				'wp-csv-to-db-import', [
				$this,
				'import_page_action',
			] );
			\add_submenu_page(
				'wp-csv-to-db',
				__( 'Fields', 'csv2db' ),
				__( 'Fields', 'csv2db' ),
				'manage_options',
				'wp-csv-to-db-fields', [
				$this,
				'fields_page_action',
			] );
			\add_submenu_page(
				'wp-csv-to-db',
				__( 'Settings', 'csv2db' ),
				__( 'Settings', 'csv2db' ),
				'manage_options',
				'wp-csv-to-db-settings', [
				$this,
				'options_page_action',
			] );
		}
	}

	/**
	 * Import CSV file by AJAX
	 *
	 * @Hook wp_ajax_import_csv
	 */
	public function wp_ajax_import_csv_hook() {
		try {
			if ( ! check_ajax_referer( 'import-csv' ) ) {
				throw new \Exception( __( 'Operation is not permitted', 'csv2db' ) );
			}
			$tmp_file_name = File::upload_file();
			if ( $tmp_file_name ) {
				if ( isset( $_POST['re-create'] ) ) {
					$res = Table::create_table( $this->options['fields'] );
					if ( is_string( $res ) ) {
						throw new \Exception( __( $res ) );
					}
				}
				$skip_lines = 0;
				if ( isset( $_POST['skip-rows'] ) ) {
					$skip_lines = intval( sanitize_text_field( $_POST['skip-rows'] ) );
				}
				$res = Table::import_file( $tmp_file_name, $this->options, $skip_lines );
				if ( is_string( $res ) ) {
					throw new \Exception( __( $res ) );
				} else {
					$results = [
						'success' => true,
						'message' => __( 'Success!', 'csv2db' ),
					];
				}
			}
		} catch ( \Exception $e ) {
			$results = [
				'success' => false,
				'message' => $e->getMessage(),
			];
		}
		File::unlink( $tmp_file_name );
		$this->parse_view( 'json', $results );
	}

	/**
	 * Analyze CSV file by AJAX
	 *
	 * @Hook wp_ajax_analyze_csv
	 */
	public function wp_ajax_analyze_csv_hook() {
		global $wp_filesystem;
		try {
			if ( ! check_ajax_referer( 'csv2db-options' ) ) {
				throw new \Exception( __( 'Operation is not permitted', 'csv2db' ) );
			}
			$tmp_file_name = File::upload_file();
			if ( $tmp_file_name && $this->check_fs() ) {
				$data   = $wp_filesystem->get_contents_array( $tmp_file_name );
				$fields = str_getcsv(
					$data[0] ?? '',
					$this->get_option( 'fields-terminated' ),
					$this->get_option( 'fields-enclosed' ),
					stripslashes( $this->get_option( 'fields-escaped' ) )
				);
				if ( ! $fields || ! count( $fields ) ) {
					throw new \Exception( __( 'Cannot detect fields', 'csv2db' ) );
				} else {
					// save fields
					$fields_data = [];
					foreach ( $fields as $field ) {
						$fields_data[] = $this->generate_empty_field( $field );
					}
					$this->options['fields'] = $fields_data;
					\update_option( self::OPTIONS_NAME, $this->options );
					$results = [
						'success' => true,
						'data'    => $fields,
						'message' => __( 'Success! Reloading...', 'csv2db' ),
					];
				}
			}
		} catch ( \Exception $e ) {
			$results = [
				'success' => false,
				'message' => $e->getMessage(),
			];
		}
		File::unlink( $tmp_file_name );
		$this->parse_view( 'json', $results );
	}

	/**
	 * Get items by AJAX
	 *
	 * @Hook wp_ajax_get_items
	 */
	public function wp_ajax_get_items_hook() {
		$results = [
			'total' => 0,
			'rows'  => [],
		];
		if ( check_ajax_referer( 'items-table' ) ) {
			$columns = $this->collect_columns_to_show( $skip_auto_generated = true );
			if ( count( $columns ) ) {
				$start = intval( $_POST['offset'] ?? 0 );
				$limit = intval( $_POST['limit'] ?? 10 );
				if ( ! $limit ) {
					$limit = 10;
				}
				$order  = sanitize_text_field( $_POST['order'] ?? 'asc' );
				$fields = array_column( $columns, 'name' );
				[ $total, $rows ] = Table::get_items( $columns, $fields, $start, $limit, $order );
				$results = [
					'total' => (int) $total,
					'rows'  => (array) $rows,
				];
			}
		}
		$this->parse_view( 'json', $results );
	}

	/**
	 * @param bool $skip_auto_generated
	 *
	 * @return array
	 */
	public function collect_columns_to_show( $skip_auto_generated = false ) {
		$columns = [];
		$checked = false;
		if ( ! empty( $this->options['fields'] ) ) {
			foreach ( $this->options['fields'] as $field ) {
				if ( ! empty( $field['show'] ) ) {
					if ( empty( $field['title'] ) ) {
						$field['title'] = $field['name'];
					}
					$columns[] = $field;
					if ( isset( $field['check'] ) ) {
						$this->data_id_field = $field['name'];
						$checked             = true;
					}
				}
			}
			usort( $columns, function ( $a, $b ) {
				return ( isset( $a['index'] ) && $a['index'] == 'PRIMARY' ) ? 0 : 1;
			} );
			if ( ! $skip_auto_generated && ! $checked ) {
				array_unshift( $columns, [
					'name'  => '__auto_generated_check_column__',
					'check' => true,
				] );
				$this->data_id_field = '__auto_generated_check_column__';
			}
		}

		return $columns;
	}

	/**
	 * Show the options page via admin menu
	 *
	 * @Slug wp-csv-to-db-settings
	 */
	public function options_page_action() {
		return $this->parse_view( 'options' );
	}

	/**
	 * Show the import page via admin menu
	 *
	 * @Slug wp-csv-to-db-import
	 */
	public function import_page_action() {
		if ( empty( $this->options['fields'] ) ) {
			$this->message = __( 'Fields undefined! Click <a href="admin.php?page=wp-csv-to-db-fields">Fields</a> to prepare fields.',
				'csv2db' );

			return $this->parse_view( 'error' );
		} else {
			return $this->parse_view( 'import' );
		}
	}

	/**
	 * Show the fields page via admin menu
	 *
	 * @Slug wp-csv-to-db-fields
	 */
	public function fields_page_action() {
		return $this->parse_view( 'fields' );
	}

	/**
	 * Show the items page via admin menu
	 *
	 * @Slug wp-csv-to-db
	 */
	public function items_page_action() {
		$columns = $this->collect_columns_to_show();
		if ( ! count( $columns ) ) {
			$this->message = __( 'Columns undefined! Click <a href="' . admin_url( 'admin.php?page=wp-csv-to-db-fields' ) . '">Fields</a> to prepare columns.',
				'csv2db' );

			return $this->parse_view( 'error' );
		} else {
			return $this->parse_view( 'items', [ 'columns' => $columns ] );
		}
	}

	/**
	 * @Action create_table
	 * @throws \Exception
	 */
	public function create_table_action() {
		if ( ! check_admin_referer( 'csv2db-options' ) ) {
			throw new \Exception( __( 'Operation is not permitted', 'csv2db' ) );
		}
		$this->save_fields_action();
		Table::create_table( $this->options['fields'] );
	}

	/**
	 * @Action save_fields
	 * @throws \Exception
	 */
	public function save_fields_action() {
		if ( ! check_admin_referer( 'csv2db-options' ) ) {
			throw new \Exception( __( 'Operation is not permitted', 'csv2db' ) );
		}
		$fields = [];
		if ( ! empty( $_POST['csv2db']['fields'] ) ) {
			foreach ( wp_unslash( $_POST['csv2db']['fields'] ?? [] ) as $field ) {
				$fields[] = array_map( 'sanitize_text_field', wp_unslash( $field ) );
			}
		}
		$this->options['fields'] = $fields;
		\update_option( self::OPTIONS_NAME, $this->options );
	}

	/**
	 * @Action import_fields
	 */
	public function import_fields_action() {
		global $wp_filesystem;
		try {
			if ( ! check_ajax_referer( 'csv2db-options' ) ) {
				throw new \Exception( __( 'Operation is not permitted', 'csv2db' ) );
			}
			$tmp_file_name = File::upload_file();
			if ( $tmp_file_name && $this->check_fs() ) {
				$content = json_decode( $wp_filesystem->get_contents( $tmp_file_name ), true );
				if ( $content ) {
					$this->options['fields'] = $content;
					\update_option( self::OPTIONS_NAME, $this->options );
					$results = [
						'success' => true,
						'message' => __( 'Success!', 'csv2db' ),
					];
				} else {
					throw new \Exception( __( 'Wrong file format' ) );
				}
			}
		} catch ( \Exception $e ) {
			$results = [
				'success' => false,
				'message' => $e->getMessage(),
			];
		}
		File::unlink( $tmp_file_name );
		$this->parse_view( 'json', $results );
	}

	/**
	 * @Action clear_fields
	 */
	public function clear_fields_action() {
		if ( ! check_admin_referer( 'csv2db-options' ) ) {
			throw new \Exception( __( 'Operation is not permitted', 'csv2db' ) );
		}
		$this->options['fields'] = [];
		\update_option( self::OPTIONS_NAME, $this->options );
	}

	/**
	 * @Action export_fields
	 */
	public function export_fields_action() {
		$this->save_fields_action();
		$content = \wp_json_encode( $this->options['fields'] );
		$this->parse_view( 'attachment', [ 'content' => $content, 'filename' => 'csv-to-db-fields.txt' ] );
	}

	/**
	 * @Action export_schema
	 */
	public function export_schema_action() {
		$this->save_fields_action();
		$content = Table::create_schema( $this->options['fields'] );
		$this->parse_view( 'attachment', [ 'content' => $content, 'filename' => 'csv-to-db-schema.sql' ] );
	}

	private function check_fs() {
		$url = wp_nonce_url( 'plugins.php' );
		if ( false === ( $creds = \request_filesystem_credentials( $url, '', false, false, null ) ) ) {
			_e( 'Could not create filesystem credentials' );

			return false;
		}
		if ( ! \WP_Filesystem( $creds ) ) {
			\request_filesystem_credentials( $url, '', true, false, null );
			_e( 'Filesystem credentials were not available' );

			return false;
		}

		return true;
	}
}
