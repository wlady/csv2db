<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Zabara <wlady2001@gmail.com>
 * Date: 10.03.22
 * Time: 10:15
 * phpcs:ignoreFile
 */

namespace CSV2DB\Models;

class Table {
	/**
	 * Create DB table from saved configuration
	 *
	 * @param array $fields
	 *
	 * @return string/bool On error returns error message
	 */
	public static function create_table( array $fields = [] ) {
		global $wpdb;

		$wpdb->query( $wpdb->prepare( 'DROP TABLE IF EXISTS `' . $wpdb->get_blog_prefix() . 'csv_to_db`' ) );
		try {
			$wpdb->query( $wpdb->prepare( self::create_schema( $fields ) ) );
		} catch ( \Exception $e ) {
			return $e->getMessage();
		}

		return $wpdb->last_error !== '' ? $wpdb->last_error : true;
	}

	/**
	 * @param $fields
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function create_schema( $fields ) {
		global $wpdb;

		$columns = [];
		$indexes = [];
		foreach ( $fields as $field ) {
			$type = $field['type'];
			$null = ( $field['null'] ?? 0 ) == 0 ? 'NOT NULL' : 'NULL';
			$ai   = ( $field['ai'] ?? 0 ) == 1 ? 'AUTO_INCREMENT' : '';
			if ( ! in_array( $type, [ 'TEXT', 'BLOB' ] ) ) {
				$type = "{$field['type']}({$field['size']}) {$null} {$ai}";
			}
			$columns[] = "`{$field['name']}` {$type}";
			if ( ! empty( $field['index'] ) ) {
				if ( $field['index'] == 'PRIMARY' ) {
					$current_index = 'PRIMARY KEY ';
				} elseif ( $field['index'] == 'UNIQUE' ) {
					$current_index = "UNIQUE KEY `{$field['name']}`";
				} else {
					$current_index = "KEY `{$field['name']}`";
				}
				$indexes[] = $current_index . "(`{$field['name']}`)";
			}
		}
		if ( count( $indexes ) ) {
			$columns = array_merge( $columns, $indexes );
		}
		if ( ! count( $columns ) ) {
			throw new \Exception( __( 'Column configuration is empty', 'csv-to-db' ) );
		}

		return 'CREATE TABLE IF NOT EXISTS `' . $wpdb->get_blog_prefix() . 'csv_to_db` (' . implode( ',', $columns ) . ')';
	}

	/**
	 * @param $file_name
	 *
	 * @return mix On error returns error message
	 */
	public static function import_file( $file_name, $options, $skip_lines = 0 ) {
		global $wpdb;

		$use_local     = $options['use-local'] == 1 ? 'LOCAL' : '';
		if ( $use_local ) {
			$wpdb->query( $wpdb->prepare( 'SET GLOBAL local_infile=1;' ) );
		}
		$fields_params = [];
		$lines_params  = [];
		if ( ! empty( $options['fields-terminated'] ) ) {
			$fields_params[] = 'TERMINATED BY \'' . $options['fields-terminated'] . '\'';
		}
		if ( ! empty( $options['fields-enclosed'] ) ) {
			$symbol = $options['fields-enclosed'];
			if ( in_array( $symbol, [ '"', "'" ] ) ) {
				$fields_params[] = 'ENCLOSED BY \'\\' . $symbol . '\'';
			}
		}
		if ( ! empty( $options['fields-escaped'] ) ) {
			$fields_params[] = 'ESCAPED BY \'' . $options['fields-escaped'] . '\'';
		}
		if ( ! empty( $options['lines-starting'] ) ) {
			$lines_params[] = 'STARTING BY \'' . $options['lines-starting'] . '\'';
		}
		if ( ! empty( $options['lines-terminated'] ) ) {
			$lines_params[] = 'TERMINATED BY \'' . $options['lines-terminated'] . '\'';
		}
		$query = 'LOAD DATA ' . $use_local . ' INFILE %s INTO TABLE `' . $wpdb->get_blog_prefix() . 'csv_to_db`';
		if ( count( $fields_params ) ) {
			$query .= ' FIELDS ' . implode( ' ', $fields_params );
		}
		if ( count( $lines_params ) ) {
			$query .= ' LINES ' . implode( ' ', $lines_params );
		}
		if ( $skip_lines > 0 ) {
			$query .= ' IGNORE ' . $skip_lines . ' LINES';
		}
		$wpdb->query( $wpdb->prepare( $query, $file_name ) );

		return $wpdb->last_error !== '' ? $wpdb->last_error : true;
	}

	/**
	 * @param $columns
	 * @param $fields
	 * @param int $start
	 * @param int $limit
	 * @param string $order
	 *
	 * @return array
	 */
	public static function get_items( $columns, $fields, $start = 0, $limit = 10, $order = 'asc' ) {
		global $wpdb;

		$res   = $wpdb->get_results(
			$wpdb->prepare( 'SELECT SQL_CALC_FOUND_ROWS `' . implode( '`,`', $fields )
			                . '` FROM `' . $wpdb->get_blog_prefix() . 'csv_to_db` LIMIT %d,%d',
				$start,
				$limit )
		);
		$total = $wpdb->get_var( 'SELECT FOUND_ROWS() AS total' );
		$rows  = self::convert_fields( $columns, $res );

		return [ $total, $rows ];
	}

	/**
	 * @param $columns
	 * @param $records
	 *
	 * @return array
	 */
	public static function convert_fields( $columns, $records ) {
		$rows = array_map( function ( $item ) use ( $columns ) {
			$row = (array) $item;
			foreach ( $row as $field => $value ) {
				$column = array_filter( $columns, function ( $col ) use ( $field ) {
					return $col['name'] == $field ? $col : null;
				} );
				$col    = array_pop( $column );
				switch ( $col['type'] ) {
					case 'INT':
						$item->{$field} = (int) $value;
						break;
					case 'FLOAT':
						$item->{$field} = (float) $value;
						break;
					case 'DOUBLE':
					case 'DECIMAL':
						$item->{$field} = (double) $value;
						break;
				}
				if ( $col['index'] == 'PRIMARY' ) {
					$item->id = (int) $value;
				}
			}

			return $item;
		}, $records );

		return $rows;
	}

	/**
	 * Called on uninstall
	 */
	public static function drop_tables() {
		global $wpdb;

		$wpdb->query( $wpdb->prepare('DROP TABLE IF EXISTS `' . $wpdb->get_blog_prefix() . 'csv_to_db`' ) );
	}
}