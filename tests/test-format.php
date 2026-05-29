<?php
/**
 * Smoke tests for Auto Copyright format substitution.
 *
 * @package Auto_Copyright
 */

class Test_Auto_Copyright_Format extends WP_UnitTestCase {

	public function setUp(): void {
		parent::setUp();
		delete_transient( 'thisismyurl_autocopyright_from_year' );
	}

	public function test_default_format_substitutes_copyright_symbol() {
		$out = thisismyurl_autocopyright();
		$this->assertStringContainsString( '&copy;', $out, 'Default output should contain the copyright entity.' );
	}

	public function test_to_placeholder_is_the_current_year_not_the_newest_post() {
		// Earliest post is old; newest published post is also in the past. #to# must
		// still resolve to the current year, never to the newest post's year.
		$old = self::factory()->post->create(
			array(
				'post_status' => 'publish',
				'post_date'   => '2010-06-01 12:00:00',
			)
		);
		self::factory()->post->create(
			array(
				'post_status' => 'publish',
				'post_date'   => '2011-05-03 12:00:00',
			)
		);

		// Bust the cache so the transient repopulates with the seeded posts.
		delete_transient( 'thisismyurl_autocopyright_from_year' );

		$current = (string) wp_date( 'Y' );
		$out     = thisismyurl_autocopyright( '#from# - #to#' );
		$this->assertSame( '2010 - ' . $current, $out );

		wp_delete_post( $old, true );
	}

	public function test_empty_site_renders_single_current_year() {
		// No published posts: the range must collapse to a single current year,
		// not render an empty "  - 2026" span.
		delete_transient( 'thisismyurl_autocopyright_from_year' );

		$current = (string) wp_date( 'Y' );
		$out     = thisismyurl_autocopyright( '#from# - #to#' );
		$this->assertSame( $current, $out );
	}

	public function test_from_year_is_cached_in_transient() {
		self::factory()->post->create(
			array(
				'post_status' => 'publish',
				'post_date'   => '2015-01-01 00:00:00',
			)
		);

		// First call populates the transient with the earliest-post year.
		thisismyurl_autocopyright_get_year_span();
		$cached = get_transient( 'thisismyurl_autocopyright_from_year' );

		$this->assertSame( '2015', $cached );
	}

	public function test_save_post_invalidates_year_cache() {
		set_transient( 'thisismyurl_autocopyright_from_year', '1999', DAY_IN_SECONDS );

		self::factory()->post->create( array( 'post_status' => 'publish' ) );

		$this->assertFalse(
			get_transient( 'thisismyurl_autocopyright_from_year' ),
			'save_post must flush the cached earliest-post year.'
		);
	}

	public function test_article_shortcode_substitutes_sitename() {
		update_option( 'blogname', 'Test Site' );

		// get_the_date requires a post context; create + go to one.
		$post_id = self::factory()->post->create(
			array(
				'post_status' => 'publish',
				'post_date'   => '2026-05-03 12:00:00',
			)
		);
		$this->go_to( get_permalink( $post_id ) );
		setup_postdata( get_post( $post_id ) );

		$out = thisismyurl_autocopyright_article( array( 'format' => '#sitename# #y#' ) );
		$this->assertStringContainsString( 'Test Site', $out );
		$this->assertStringContainsString( '2026', $out );

		wp_reset_postdata();
	}
}
