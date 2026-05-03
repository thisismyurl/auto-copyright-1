<?php
/**
 * PHPUnit bootstrap for Auto Copyright.
 *
 * Wires up wp-phpunit (https://github.com/wp-phpunit/wp-phpunit) and loads
 * the plugin under test. Run from the plugin root:
 *
 *     composer require --dev wp-phpunit/wp-phpunit yoast/phpunit-polyfills
 *     export WP_TESTS_DIR=/path/to/wordpress-tests
 *     vendor/bin/phpunit
 *
 * @package Auto_Copyright
 */

$wp_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $wp_tests_dir ) {
	$wp_tests_dir = sys_get_temp_dir() . '/wordpress-tests-lib';
}

if ( ! file_exists( $wp_tests_dir . '/includes/functions.php' ) ) {
	fwrite( STDERR, "Could not find WP test library at {$wp_tests_dir}. Set WP_TESTS_DIR.\n" );
	exit( 1 );
}

require_once $wp_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin under test.
 */
function _manually_load_plugin() {
	require dirname( __DIR__ ) . '/auto-copyright-1.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

require $wp_tests_dir . '/includes/bootstrap.php';
