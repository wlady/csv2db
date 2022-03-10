<?php
/**
 * Plugin Name: CSV To DB
 * Plugin URI: https://github.com/wlady/csv2db
 * Description: Import CSV file into DB
 * Author: Vladimir Zabara <wlady2001@gmail.com>
 * Author URI: https://github.com/wlady
 * Version: 2.0.0
 * Text Domain: csv2db
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package CSV2DB
 */

namespace CSV2DB;

defined( 'ABSPATH' ) or exit;

define( 'CSV2DB_VERSION', '2.0.0' );
define( 'CSV2DB_SUPPORT_PHP', '7.4' );
define( 'CSV2DB_SUPPORT_WP', '5.0' );

define( 'CSV2DB_DEBUG', true );
define( 'CSV2DB_FILEPATH', dirname( __FILE__ ) );
define( 'CSV2DB_ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR );
define( 'CSV2DB_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR );
define( 'CSV2DB_URL', plugin_dir_url( __FILE__ ) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR );
define( 'CSV2DB_ASSETS_URL', plugin_dir_url( __FILE__ ) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR );
define( 'CSV2DB_VENDDOR_URL', plugin_dir_url( __FILE__ ) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR );
define( 'CSV2DB_VIEW_PATH',
	dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR );
define( 'CSV2DB_PLUGIN_NAME', plugin_basename( __FILE__ ) );

include( dirname( __FILE__ ) . '/vendor/autoload.php' );

$config = [
	'plugin_file'     => __FILE__,
	'plugin_basename' => CSV2DB_PLUGIN_NAME,
	'plugin_slug'     => basename( __DIR__ ),
	'plugin_dir'      => __DIR__,
];

new Base( $config );
