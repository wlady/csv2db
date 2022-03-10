<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Zabara <wlady2001@gmail.com>
 * Date: 10.03.22
 * Time: 10:15
 */

namespace CSV2DB;

defined( 'ABSPATH' ) || exit;

class Helper {

	/**
	 * Check WordPress version
	 *
	 * @access public
	 *
	 * @param string $version
	 *
	 * @return bool
	 */
	public static function wp_version_gte( $version ) {
		$wp_version = get_bloginfo( 'version' );

		// Treat release candidate strings
		$wp_version = preg_replace( '/-RC.+/i', '', $wp_version );

		if ( $wp_version ) {
			return version_compare( $wp_version, $version, '>=' );
		}

		return false;
	}

	/**
	 * Check PHP version
	 *
	 * @access public
	 *
	 * @param string $version
	 *
	 * @return bool
	 */
	public static function php_version_gte( $version ) {
		return version_compare( PHP_VERSION, $version, '>=' );
	}

	/**
	 * Check if environment meets requirements
	 *
	 * @access public
	 * @return bool
	 */
	public static function check_environment() {
		$is_ok = true;

		// Check PHP version
		if ( ! self::php_version_gte( CSV2DB_SUPPORT_PHP ) ) {
			add_action( 'admin_notices', function () {
				echo '<div class="error"><p>'
				     . sprintf( __( '<strong>CSV To DB</strong> requires PHP version %s or later.',
						'csv2db' ), CSV2DB_SUPPORT_PHP )
				     . '</p></div>';
			} );
			$is_ok = false;
		}

		// Check WordPress version
		if ( ! self::wp_version_gte( CSV2DB_SUPPORT_WP ) ) {
			add_action( 'admin_notices', function () {
				echo '<div class="error"><p>'
				     . sprintf( __( '<strong>CSV To DB</strong> requires WordPress version %s or later.',
						'csv2db' ), CSV2DB_SUPPORT_WP )
				     . '</p></div>';
			} );
			$is_ok = false;
		}

		return $is_ok;
	}

	public static function human_filesize($size, $precision = 2) {
		for ($i = 0; ($size / 1024) > 0.9; $i++, $size /= 1024) {}

		return round($size, $precision).['B','kB','MB','GB','TB','PB','EB','ZB','YB'][$i];
	}
}