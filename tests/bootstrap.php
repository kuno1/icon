<?php
/**
 * PHPUnit bootstrap file.
 *
 * @package Kunoichi\Icon
 */

// In wp-env the WordPress test library lives at /wordpress-phpunit/.
$_tests_dir = getenv( 'WP_TESTS_DIR' ) ?: '/wordpress-phpunit';

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find {$_tests_dir}/includes/functions.php. Run the tests inside wp-env (npm test)." . PHP_EOL;
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

// Load the library via Composer autoloader before WordPress finishes loading.
tests_add_filter( 'muplugins_loaded', function () {
	require_once dirname( __DIR__ ) . '/vendor/autoload.php';
} );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
