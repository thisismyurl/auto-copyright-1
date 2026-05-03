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

global $default_format;
$default_format = 'format=Copyright ( #c# ) #from# - #to#';

function thisismyurl_autocopyright_article( $arg = NULL ) {

	$defaults = array(
					'return_type' => 'return',
					'format' => '#c# #y# #sitename#. All Rights Reserved.'
				);
	$option = wp_parse_args( $arg, $defaults );

	global $post;

	$format = str_replace( '#c#', '&copy;', $option['format'] );
	$format = str_replace( '#y#', get_the_date( 'Y' ), $format );
	$format = str_replace( '#sitename#', get_bloginfo( 'sitename' ), $format );
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
function autocopyright( $format_result=NULL ) {

	return thisismyurl_autocopyright( $format_result );

}
}


function thisismyurl_autocopyright( $format_result=NULL ) {

	global $default_format;

	$from = '';
	$to = '';
	
	if ( empty( $format_result ) )
		$format_result = $default_format;

	/* get the first post in the database */
	$from_posts = get_posts( 'post_status=publish&order=ASC&numberposts=1' );
	if( isset( $from_posts ) )
		$from = get_the_time( 'Y', $from_posts[0]->ID );

	/* get the last post in the database */
	$to_posts = get_posts( 'post_status=publish&order=DESC&numberposts=1' );
	if( isset( $to_posts ) )
		$to = get_the_time( 'Y', $to_posts[0]->ID );


	$format_result = str_replace( 'format=', '', $format_result );
	$format_result = str_replace( '#from#',$from, $format_result );
	$format_result = str_replace( '#to#',$to, $format_result );
	$format_result = str_replace( '#c#',"&copy;", $format_result );

	return $format_result;

}






class thisismyurlAutoCopyrightWidget extends WP_Widget
{

	public function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_thisismyurl_autocopyright',
			'description' => __( 'Adds a automatic copyright notice to your widget area' ),
		);
		parent::__construct( 'thisismyurl_autocopyright', __( 'Auto Copyright' ), $widget_ops );
	}

	function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
	$format = $instance['format'];

	global $default_format;

	if ( empty( $format ) )
		$format = $default_format;
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:');?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
  <p><label for="<?php echo $this->get_field_id('format'); ?>"><?php _e( 'Format:');?> <input class="widefat" id="<?php echo $this->get_field_id('format'); ?>" name="<?php echo $this->get_field_name('format'); ?>" type="text" value="<?php echo esc_attr($format); ?>" /></label><br/>
  <em><a href="<?php echo plugins_url( 'readme.txt', __FILE__ );?>"><?php _e( 'Learn about format options.');?></a></em></p>

<?php
  }

  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
	$instance['format'] = $new_instance['format'];
    return $instance;
  }

  function widget( $args, $instance ) {
    extract( $args, EXTR_SKIP );

    echo $before_widget;
    $title = empty( $instance['title'] ) ? ' ' : apply_filters( 'widget_title', $instance['title']) ;

	$format = $instance['format'];
	global $default_format;

	if ( empty( $format ) )
		$format = $default_format;

    if ( ! empty( $title ) )
	    echo $before_title . $title . $after_title;

	echo thisismyurl_autocopyright( $format );


    echo $after_widget;
  }

}

function thisismyurlAutoCopyrightWidget_init() {
	register_widget( 'thisismyurlAutoCopyrightWidget' );
}
add_action( 'widgets_init', 'thisismyurlAutoCopyrightWidget_init' );
