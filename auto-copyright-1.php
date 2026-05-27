<?php
/**
 * Plugin Name:  This Is My URL - Auto Copyright
 * Plugin URI:   https://thisismyurl.com/downloads/auto-copyright-1/
 * Description:  Automates the copyright notice for websites.
 * Author:       Christopher Ross
 * Author URI:   https://thisismyurl.com/
 * Version:      1.6147
 * Requires at least: 5.6
 * Requires PHP: 7.4
 * Tested up to: 6.9
 * License:      GPL-2.0-or-later
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  auto-copyright-1
 * Domain Path:  /languages
 *
 * @package   Auto_Copyright
 * @copyright Copyright (c) 2008, Christopher Ross
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @since     1.0.0
 */

declare( strict_types=1 );

namespace ThisIsMyURL\AutoCopyright {

defined( 'ABSPATH' ) || exit;

	/**
	 * Default format string used when no format is supplied.
	 *
	 * Includes the legacy `format=` prefix so it passes through the strip
	 * in thisismyurl_autocopyright() transparently.
	 */
	const DEFAULT_FORMAT = 'format=Copyright ( #c# ) #from# - #to#';

	// -------------------------------------------------------------------------
	// Template functions
	// -------------------------------------------------------------------------

	/**
	 * Per-article copyright string. Use inside the Loop.
	 *
	 * @param string|array|null $arg Format string or args array.
	 * @return string Rendered copyright.
	 */
	function thisismyurl_autocopyright_article( $arg = null ): string {
		$defaults = array(
			'return_type' => 'return',
			'format'      => '#c# #y# #sitename#. All Rights Reserved.',
		);
		$option = \wp_parse_args( $arg, $defaults );

		$format = \str_replace( '#c#', '&copy;', $option['format'] );
		$format = \str_replace( '#y#', \get_the_date( 'Y' ), $format );
		$format = \str_replace( '#sitename#', \get_bloginfo( 'name' ), $format );

		return $format;
	}
	\add_shortcode( 'thisismyurl_autocopyright_article', __NAMESPACE__ . '\thisismyurl_autocopyright_article' );

	/**
	 * Site-wide copyright notice spanning earliest published post to most recent.
	 *
	 * @param string|null $format_result Format string. Supports #c# #from# #to# placeholders.
	 *                                   Pass null to use the DEFAULT_FORMAT constant.
	 * @return string Rendered notice.
	 */
	function thisismyurl_autocopyright( ?string $format_result = null ): string {
		if ( null === $format_result || '' === $format_result ) {
			$format_result = DEFAULT_FORMAT;
		}

		$years = thisismyurl_autocopyright_get_year_span();
		$from  = $years['from'];
		$to    = $years['to'];

		$format_result = \str_replace( 'format=', '', $format_result );
		$format_result = \str_replace( '#from#', $from, $format_result );
		$format_result = \str_replace( '#to#', $to, $format_result );
		$format_result = \str_replace( '#c#', '&copy;', $format_result );

		return $format_result;
	}

	// -------------------------------------------------------------------------
	// Year-span helpers
	// -------------------------------------------------------------------------

	/**
	 * Earliest / latest published-post years, cached in a transient.
	 *
	 * Invalidated on save_post / deleted_post / trashed_post.
	 *
	 * @return array{from:string,to:string}
	 */
	function thisismyurl_autocopyright_get_year_span(): array {
		$cached = \get_transient( 'thisismyurl_autocopyright_year_span' );
		if ( false !== $cached && \is_array( $cached ) ) {
			return $cached;
		}

		$from = '';
		$to   = '';

		$from_posts = \get_posts(
			array(
				'post_status'      => 'publish',
				'order'            => 'ASC',
				'orderby'          => 'date',
				'numberposts'      => 1,
				'fields'           => 'ids',
				'no_found_rows'    => true,
				'suppress_filters' => false,
			)
		);
		if ( ! empty( $from_posts ) ) {
			$from = (string) \get_the_time( 'Y', $from_posts[0] );
		}

		$to_posts = \get_posts(
			array(
				'post_status'      => 'publish',
				'order'            => 'DESC',
				'orderby'          => 'date',
				'numberposts'      => 1,
				'fields'           => 'ids',
				'no_found_rows'    => true,
				'suppress_filters' => false,
			)
		);
		if ( ! empty( $to_posts ) ) {
			$to = (string) \get_the_time( 'Y', $to_posts[0] );
		}

		$span = array(
			'from' => $from,
			'to'   => $to,
		);

		\set_transient( 'thisismyurl_autocopyright_year_span', $span, \DAY_IN_SECONDS );

		return $span;
	}

	/**
	 * Invalidate the cached year span when posts change.
	 */
	function thisismyurl_autocopyright_flush_year_cache(): void {
		\delete_transient( 'thisismyurl_autocopyright_year_span' );
	}
	\add_action( 'save_post',    __NAMESPACE__ . '\thisismyurl_autocopyright_flush_year_cache' );
	\add_action( 'deleted_post', __NAMESPACE__ . '\thisismyurl_autocopyright_flush_year_cache' );
	\add_action( 'trashed_post', __NAMESPACE__ . '\thisismyurl_autocopyright_flush_year_cache' );

	// -------------------------------------------------------------------------
	// Shortcode
	// -------------------------------------------------------------------------

	/**
	 * [auto_copyright] shortcode — clean public-facing tag.
	 *
	 * Documented as `[auto_copyright]` in README.md. Accepts the same
	 * `format=...` string the template function does.
	 *
	 * @param array|string $atts Shortcode attributes.
	 * @return string Rendered notice.
	 */
	function thisismyurl_autocopyright_shortcode( $atts = array() ): string {
		$atts = \shortcode_atts(
			array( 'format' => '' ),
			$atts,
			'auto_copyright'
		);
		$format = '' === $atts['format'] ? null : $atts['format'];

		return thisismyurl_autocopyright( $format );
	}
	\add_shortcode( 'auto_copyright', __NAMESPACE__ . '\thisismyurl_autocopyright_shortcode' );

	// -------------------------------------------------------------------------
	// Widget
	// -------------------------------------------------------------------------

	/**
	 * Auto Copyright widget.
	 */
	class Auto_Copyright_Widget extends \WP_Widget {

		public function __construct() {
			$widget_ops = array(
				'classname'   => 'widget_thisismyurl_autocopyright',
				'description' => \__( 'Adds a automatic copyright notice to your widget area', 'auto-copyright-1' ),
			);
			parent::__construct(
				'thisismyurl_autocopyright',
				\__( 'Auto Copyright', 'auto-copyright-1' ),
				$widget_ops
			);
		}

		/** @return string */
		public function form( $instance ) {
			$instance = \wp_parse_args(
				(array) $instance,
				array(
					'title'  => '',
					'format' => '',
				)
			);
			$title  = $instance['title'];
			$format = '' !== $instance['format'] ? $instance['format'] : DEFAULT_FORMAT;
			?>
			<p>
				<label for="<?php echo \esc_attr( $this->get_field_id( 'title' ) ); ?>">
					<?php \esc_html_e( 'Title:', 'auto-copyright-1' ); ?>
					<input class="widefat"
					       id="<?php echo \esc_attr( $this->get_field_id( 'title' ) ); ?>"
					       name="<?php echo \esc_attr( $this->get_field_name( 'title' ) ); ?>"
					       type="text"
					       value="<?php echo \esc_attr( $title ); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo \esc_attr( $this->get_field_id( 'format' ) ); ?>">
					<?php \esc_html_e( 'Format:', 'auto-copyright-1' ); ?>
					<input class="widefat"
					       id="<?php echo \esc_attr( $this->get_field_id( 'format' ) ); ?>"
					       name="<?php echo \esc_attr( $this->get_field_name( 'format' ) ); ?>"
					       type="text"
					       value="<?php echo \esc_attr( $format ); ?>" />
				</label>
				<br />
				<em>
					<a href="<?php echo \esc_url( \plugins_url( 'readme.txt', __FILE__ ) ); ?>">
						<?php \esc_html_e( 'Learn about format options.', 'auto-copyright-1' ); ?>
					</a>
				</em>
			</p>
			<?php
			return '';
		}

		/** @return array */
		public function update( $new_instance, $old_instance ) {
			$instance           = $old_instance;
			$instance['title']  = isset( $new_instance['title'] ) ? \sanitize_text_field( $new_instance['title'] ) : '';
			$instance['format'] = isset( $new_instance['format'] ) ? \wp_kses_post( $new_instance['format'] ) : '';
			return $instance;
		}

		/** @return void */
		public function widget( $args, $instance ) {
			echo isset( $args['before_widget'] ) ? $args['before_widget'] : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme-supplied chrome.

			$title  = empty( $instance['title'] ) ? ' ' : \apply_filters( 'widget_title', $instance['title'] );
			$format = ( isset( $instance['format'] ) && '' !== $instance['format'] )
				? $instance['format']
				: DEFAULT_FORMAT;

			if ( '' !== \trim( (string) $title ) ) {
				$before_title = isset( $args['before_title'] ) ? $args['before_title'] : '';
				$after_title  = isset( $args['after_title'] ) ? $args['after_title'] : '';
				echo $before_title . \esc_html( $title ) . $after_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme-supplied chrome.
			}

			echo \wp_kses_post( thisismyurl_autocopyright( $format ) );

			echo isset( $args['after_widget'] ) ? $args['after_widget'] : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme-supplied chrome.
		}
	}

	/**
	 * Register the Auto Copyright widget.
	 */
	function register_widgets(): void {
		\register_widget( Auto_Copyright_Widget::class );
	}
	\add_action( 'widgets_init', __NAMESPACE__ . '\register_widgets' );

	// -------------------------------------------------------------------------
	// i18n
	// -------------------------------------------------------------------------

	/**
	 * Load text domain for translations.
	 */
	function load_textdomain(): void {
		\load_plugin_textdomain(
			'auto-copyright-1',
			false,
			\dirname( \plugin_basename( __FILE__ ) ) . '/languages'
		);
	}
	\add_action( 'init', __NAMESPACE__ . '\load_textdomain' );

	// -------------------------------------------------------------------------
	// Plugin-row action links
	// -------------------------------------------------------------------------

	/**
	 * Append a Sponsor link to the plugin row actions.
	 *
	 * @param array<string> $links Existing action links.
	 * @return array<string>
	 */
	function add_action_links( array $links ): array {
		$links[] = '<a href="' . \esc_url( 'https://github.com/sponsors/thisismyurl' ) . '" target="_blank" rel="noopener noreferrer">' . \esc_html__( 'Sponsor', 'auto-copyright-1' ) . '</a>';
		return $links;
	}
	\add_filter( 'plugin_action_links_' . \plugin_basename( __FILE__ ), __NAMESPACE__ . '\add_action_links' );

} // end namespace ThisIsMyURL\AutoCopyright

// =============================================================================
// Global-namespace bridges
//
// These thin wrappers keep the public template-tag API intact for themes that
// call thisismyurl_autocopyright() / thisismyurl_autocopyright_article() /
// autocopyright() directly, without knowing about the PHP namespace.
// =============================================================================

namespace {

	if ( ! function_exists( 'thisismyurl_autocopyright' ) ) {
		/**
		 * Site-wide copyright notice (global-namespace bridge).
		 *
		 * @param string|null $format_result Format string; null uses the default.
		 * @return string
		 */
		function thisismyurl_autocopyright( ?string $format_result = null ): string {
			return \ThisIsMyURL\AutoCopyright\thisismyurl_autocopyright( $format_result );
		}
	}

	if ( ! function_exists( 'thisismyurl_autocopyright_article' ) ) {
		/**
		 * Per-article copyright string (global-namespace bridge). Use inside the Loop.
		 *
		 * @param string|array|null $arg Format string or args array.
		 * @return string
		 */
		function thisismyurl_autocopyright_article( $arg = null ): string {
			return \ThisIsMyURL\AutoCopyright\thisismyurl_autocopyright_article( $arg );
		}
	}

	if ( ! function_exists( 'autocopyright' ) ) {
		/**
		 * Legacy alias for thisismyurl_autocopyright(). Documented in README.md.
		 *
		 * @param string|null $format_result Format string; null uses the default.
		 * @return string
		 */
		function autocopyright( ?string $format_result = null ): string {
			return \ThisIsMyURL\AutoCopyright\thisismyurl_autocopyright( $format_result );
		}
	}

} // end global namespace
