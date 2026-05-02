# Auto Copyright

[![CI](https://github.com/thisismyurl/auto-copyright-1/actions/workflows/ci.yml/badge.svg)](https://github.com/thisismyurl/auto-copyright-1/actions/workflows/ci.yml) [![WordPress Tested](https://img.shields.io/badge/WordPress-4.1%2B-blue)](https://wordpress.org/) [![License](https://img.shields.io/badge/License-GPL--2.0-blue)](LICENSE)


Auto-generates a WordPress footer copyright notice spanning your first published post to the current year — shortcode and template tag.

## The short story

I wrote this plugin in 2008. In January 2016, Phill Coxon took it over and maintained it through the WordPress 4.x era. The plugin's been quiet for a while and I've brought it back home to keep it working on modern WordPress.

Same plugin, same `WP_Query` against `post_status=publish` to find the earliest post date, same year-span output, same shortcode and template-tag entry points. The maintenance is mine again.

## What it does

- Calculates the year range from your earliest published post to today.
- Renders a configurable copyright notice via `[auto_copyright]` shortcode or `thisismyurl_autocopyright()` template tag.
- Per-article variant via `thisismyurl_autocopyright_article()` for individual posts inside the Loop.
- Format string supports `#c#` (©), `#y#` (current year), `#sitename#`, `#from#` (earliest year), `#to#` (current year).

## What it doesn't do

- It doesn't store the calculated year — recalculates on each render. For a high-traffic footer, the calculation is one cached `WP_Query`, so the cost is negligible.
- It doesn't handle multi-site copyright spans across a network — single-site only.

## Installation

- WordPress.org plugin directory (recommended): [Auto Copyright](https://wordpress.org/plugins/auto-copyright-1/)
- Manual: download the latest release, upload to `wp-content/plugins/`, activate.

## Maintained by

Christopher Ross, on the open web since 1996 and on WordPress since 2007. More at [thisismyurl.com](https://thisismyurl.com/).

## License

GPL-2.0-or-later. The original copyright remains with Christopher Ross (2008); maintenance contributions through 2016 are credited to Phill Coxon in the changelog.
