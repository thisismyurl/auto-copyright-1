# Changelog

All notable changes to this project will be documented in this file.

## [0.6123] - 2026-05-03

Maintenance returns to Christopher Ross. First modernization pass after the WordPress 4.x dormancy.

### Fixed

- **PHP fatal**: shortcode handler `thisismyurl_autocopyright_article()` read `$options` before assignment; `wp_parse_args()` already handles `key=value&key=value` strings, so the manual explode block has been removed.
- **PHP fatal**: widget class used PHP4-style constructor `function thisismyurlAutoCopyrightWidget()` and `$this->WP_Widget(...)` (both removed in PHP 8 / WP 4.3+). Replaced with `__construct()` + `parent::__construct( 'thisismyurl_autocopyright', ... )`.
- **Widget output**: `$intance` typo (sic) caused widget to ignore user-selected format and always fall back to default. Now reads `$instance['format']` correctly.
- **Removed function**: `attribute_escape()` (deprecated WP 2.8) replaced with `esc_attr()`.
- **Bloginfo key**: `get_bloginfo( 'sitename' )` (invalid key, returned empty) replaced with `get_bloginfo( 'name' )`.

### Security / hardening

- Escape widget instance fields with `esc_attr()` on every output.
- Pass rendered copyright notice through `wp_kses_post()` before echo.
- Add `defined( 'ABSPATH' ) || exit;` guard.
- Replace `extract( $args, EXTR_SKIP )` with explicit `$args['before_widget']` etc. (banned by WPCS).

### Performance

- Cache earliest published-post year in a transient; invalidated on `save_post` so the year span stays accurate.

### Compatibility

- Add full modern plugin header: `Text Domain`, `Domain Path`, `Requires at least`, `Requires PHP`, `License`, `License URI`.
- Load text domain on `init` and add `'auto-copyright-1'` to every translation call.
- Register clean `[auto_copyright]` shortcode; keep `[thisismyurl_autocopyright_article]` as alias for back-compat.

### Docs / metadata

- `readme.txt` contributors: `thisismyurl, phillcoxon` (preserve historical credit).
- Tags trimmed from 14 to the .org-allowed 5: `copyright, footer, shortcode, widget, year`.
- `Tested up to: 6.8`, `Requires PHP: 7.4`, `Stable tag: 0.6123`.
- Drop duplicate `== Screenshots ==` block, empty `== Updates ==` section, and dead Phill Coxon donate link.
- Fix `Chrsitopher` typo in file docblock.

### Repo hygiene

- Add `.distignore`, `SECURITY.md`, `CONTRIBUTING.md`, `.gitignore`.
- CI workflow no longer swallows lint failures with `|| true`.

### Notes

- Version scheme adopted: `x.Yddd` where `x` = release class (`0` = pre-release, `1` = full), `Y` = last digit of year, `ddd` = Julian day. Today: `0.6123` = pre-release, year 6 (2026), day 123 (May 3).
- Original `14.11` version was a date-based scheme from the Phill Coxon era; the jump to `0.6123` is intentional and signals the modernization re-baseline.
