<?php
/*
Plugin Name: Auto Copyright
Plugin URI: https://thisismyurl.com/downloads/auto-copyright-1/
Description: Automates the copyright notice for websites.
Author: Christopher Ross
Author URI: https://thisismyurl.com/
Version: 0.6123
Requires at least: 5.6
Requires PHP: 7.4
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: auto-copyright-1
Domain Path: /languages
*/

/**
 * Auto Copyright
 *
 * This file contains all the logic required for the plugin
 *
 * @link		https://wordpress.org/plugins/auto-copyright-1/
 *
 * @package 	Auto Copyright
 * @copyright	Copyright (c) 2008, Christopher Ross
 * @license		https://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		Auto Copyright 1.0
 */

defined( 'ABSPATH' ) || exit;

global $default_format;
$default_format = 'format=Copyright ( #c# ) #from# - #to#';

/**
 * Per-article copyright string. Use inside the Loop.
 *
 * @param string|array|null $arg Format string or args array.
 * @return string Rendered copyright.
 */
function thisismyurl_autocopyright_article( $arg = null ) {

	$defaults = array(
		'return_type' => 'return',
		'format'      => '#c# #y# #sitename#. All Rights Reserved.',
	);
	$option   = wp_parse_args( $arg, $defaults );

	$format = str_replace( '#c#', '&copy;', $option['format'] );
	$format = str_replace( '#y#', get_the_date( 'Y' ), $format );
	$format = str_replace( '#sitename#', get_bloginfo( 'name' ), $format );

	return $format;
}
add_shortcode( 'thisismyurl_autocopyright_article', 'thisismyurl_autocopyright_article' );


if ( ! function_exists( 'autocopyright' ) ) {
	/**
	 * Legacy function no longer used
	 *
	 * @param	String	$format_result	The format to return
	 *
	 */
	function autocopyright( $format_result = null ) {
		return thisismyurl_autocopyright( $format_result );
	}
}


/**
 * Site-wide copyright notice spanning earliest published post to most recent.
 *
 * @param string|null $format_result Format string. Supports #c# #from# #to# placeholders.
 * @return string Rendered notice.
 */
function thisismyurl_autocopyright( $format_result = null ) {

	global $default_format;

	if ( empty( $format_result ) ) {
		$format_result = $default_format;
	}

	$years = thisismyurl_autocopyright_get_year_span();
	$from  = $years['from'];
	$to    = $years['to'];

	$format_result = str_replace( 'format=', '', $format_result );
	$format_result = str_replace( '#from#', $from, $format_result );
	$format_result = str_replace( '#to#', $to, $format_result );
	$format_result = str_replace( '#c#', '&copy;', $format_result );

	return $format_result;
}

/**
 * Earliest / latest published-post years, cached in a transient.
 *
 * Invalidated on save_post (see thisismyurl_autocopyright_flush_year_cache).
 *
 * @return array{from:string,to:string}
 */
function thisismyurl_autocopyright_get_year_span() {

	$cached = get_transient( 'thisismyurl_autocopyright_year_span' );
	if ( false !== $cached && is_array( $cached ) ) {
		return $cached;
	}

	$from = '';
	$to   = '';

	$from_posts = get_posts(
		array(
			'post_status'    => 'publish',
			'order'          => 'ASC',
			'orderby'        => 'date',
			'numberposts'    => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'suppress_filters' => false,
		)
	);
	if ( ! empty( $from_posts ) ) {
		$from = get_the_time( 'Y', $from_posts[0] );
	}

	$to_posts = get_posts(
		array(
			'post_status'    => 'publish',
			'order'          => 'DESC',
			'orderby'        => 'date',
			'numberposts'    => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'suppress_filters' => false,
		)
	);
	if ( ! empty( $to_posts ) ) {
		$to = get_the_time( 'Y', $to_posts[0] );
	}

	$span = array(
		'from' => (string) $from,
		'to'   => (string) $to,
	);

	set_transient( 'thisismyurl_autocopyright_year_span', $span, DAY_IN_SECONDS );

	return $span;
}

/**
 * Invalidate the cached year span when posts change.
 */
function thisismyurl_autocopyright_flush_year_cache() {
	delete_transient( 'thisismyurl_autocopyright_year_span' );
}
add_action( 'save_post', 'thisismyurl_autocopyright_flush_year_cache' );
add_action( 'deleted_post', 'thisismyurl_autocopyright_flush_year_cache' );
add_action( 'trashed_post', 'thisismyurl_autocopyright_flush_year_cache' );

/**
 * [auto_copyright] shortcode — clean public-facing tag.
 *
 * Documented as `[auto_copyright]` in README.md. Accepts the same `format=...`
 * string the template tag does.
 *
 * @param array|string $atts Shortcode attributes.
 * @return string Rendered notice.
 */
function thisismyurl_autocopyright_shortcode( $atts = array() ) {
	$atts   = shortcode_atts(
		array(
			'format' => '',
		),
		$atts,
		'auto_copyright'
	);
	$format = '' === $atts['format'] ? null : $atts['format'];

	return thisismyurl_autocopyright( $format );
}
add_shortcode( 'auto_copyright', 'thisismyurl_autocopyright_shortcode' );


/**
 * Auto Copyright widget.
 */
class thisismyurlAutoCopyrightWidget extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_thisismyurl_autocopyright',
			'description' => __( 'Adds a automatic copyright notice to your widget area', 'auto-copyright-1' ),
		);
		parent::__construct( 'thisismyurl_autocopyright', __( 'Auto Copyright', 'auto-copyright-1' ), $widget_ops );
	}

	public function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'  => '',
				'format' => '',
			)
		);
		$title    = $instance['title'];
		$format   = $instance['format'];

		global $default_format;
		if ( empty( $format ) ) {
			$format = $default_format;
		}
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Title:', 'auto-copyright-1' ); ?>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'format' ) ); ?>">
				<?php esc_html_e( 'Format:', 'auto-copyright-1' ); ?>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'format' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'format' ) ); ?>" type="text" value="<?php echo esc_attr( $format ); ?>" />
			</label>
			<br />
			<em>
				<a href="<?php echo esc_url( plugins_url( 'readme.txt', __FILE__ ) ); ?>">
					<?php esc_html_e( 'Learn about format options.', 'auto-copyright-1' ); ?>
				</a>
			</em>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance           = $old_instance;
		$instance['title']  = isset( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['format'] = isset( $new_instance['format'] ) ? wp_kses_post( $new_instance['format'] ) : '';
		return $instance;
	}

	public function widget( $args, $instance ) {
		echo isset( $args['before_widget'] ) ? $args['before_widget'] : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme-supplied chrome.
		$title = empty( $instance['title'] ) ? ' ' : apply_filters( 'widget_title', $instance['title'] );

		$format = isset( $instance['format'] ) ? $instance['format'] : '';
		global $default_format;
		if ( empty( $format ) ) {
			$format = $default_format;
		}

		if ( ! empty( trim( $title ) ) ) {
			$before_title = isset( $args['before_title'] ) ? $args['before_title'] : '';
			$after_title  = isset( $args['after_title'] ) ? $args['after_title'] : '';
			echo $before_title . esc_html( $title ) . $after_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme-supplied chrome.
		}

		echo wp_kses_post( thisismyurl_autocopyright( $format ) );

		echo isset( $args['after_widget'] ) ? $args['after_widget'] : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme-supplied chrome.
	}
}

function thisismyurlAutoCopyrightWidget_init() {
	register_widget( 'thisismyurlAutoCopyrightWidget' );
}
add_action( 'widgets_init', 'thisismyurlAutoCopyrightWidget_init' );

/**
 * Load text domain for translations.
 */
function thisismyurl_autocopyright_load_textdomain() {
	load_plugin_textdomain( 'auto-copyright-1', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'thisismyurl_autocopyright_load_textdomain' );
