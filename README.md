# This Is My URL - Auto Copyright

[![CI](https://github.com/thisismyurl/auto-copyright-1/actions/workflows/ci.yml/badge.svg)](https://github.com/thisismyurl/auto-copyright-1/actions/workflows/ci.yml) [![WordPress](https://img.shields.io/badge/WordPress-5.6%2B-blue)](https://wordpress.org/plugins/auto-copyright-1/) [![License](https://img.shields.io/badge/License-GPL--2.0-blue)](LICENSE)

Auto-generates a WordPress footer copyright notice spanning your first published post to the current year — shortcode and template tag.

## The short story

I wrote this plugin in 2008. In January 2016, Phill Coxon took it over and maintained it between 2016 and 2019. The plugin had been quiet for a while, and I've brought it back home to keep it working on modern WordPress.

Same plugin, same `WP_Query` against `post_status=publish` to find the earliest post date, same year-span output, same shortcode and template-tag entry points. The maintenance is mine again.

## What it does

- Calculates the year range from your earliest published post to today.
- Renders a configurable copyright notice via the `[auto_copyright]` shortcode or the `thisismyurl_autocopyright()` template tag.
- Provides a per-article variant through the `[thisismyurl_autocopyright_article]` shortcode (kept as the legacy long-form tag) or the `thisismyurl_autocopyright_article()` template tag for individual posts inside the Loop.
- Supports a format string with `#c#` (©), `#y#` (current year), `#sitename#`, `#from#` (earliest year), and `#to#` (current year).

## What it doesn't do

- It doesn't store the calculated year — it recalculates on each render. For a high-traffic footer, the earliest-post lookup is cached in a transient, so the cost is negligible.
- It doesn't handle multi-site copyright spans across a network — single-site only.

> **Block themes (FSE):** the bundled "Auto Copyright" widget is a classic `WP_Widget` and does not appear in the Site Editor on block themes (the WordPress 6.x / 7.0 default). On a block theme, use the `[auto_copyright]` shortcode (via a Shortcode block) or the `thisismyurl_autocopyright()` template tag in a footer template part. The widget is retained for classic themes.

## Requirements

- WordPress 5.6+
- PHP 7.4+

## Installation

- WordPress.org plugin directory (recommended): [Auto Copyright](https://wordpress.org/plugins/auto-copyright-1/)
- Manual: download the latest release, upload it to `wp-content/plugins/`, and activate.

## Changelog

See [releases](../../releases) or [readme.txt](readme.txt).

---

## Support and donations

I build these tools because WordPress sites in the wild keep hitting the same problems, and a small, focused plugin is usually the right fix. They're free to use, with no tracking and no ads.

If one of them saves you time, here are the genuine ways to help:

- **Sponsor the work.** [GitHub Sponsors](https://github.com/sponsors/thisismyurl) is the simplest way, and the Sponsor button at the top of this repo lists it alongside Bitcoin, Dogecoin, PayPal, and Interac e-transfer. Any amount helps, and none of it is expected.
- **Contribute code or ideas.** A pull request, a bug report, or a tested edge case is worth as much as a donation. See [CONTRIBUTING.md](CONTRIBUTING.md) to get started.
- **Share it.** A note on [WordPress.org](https://profiles.wordpress.org/thisismyurl/), [GitHub](https://github.com/thisismyurl), or [LinkedIn](https://linkedin.com/in/thisismyurl) helps other people find work that might save them the same afternoon.

### Report issues and questions

- **Found a bug or want a feature?** Open an issue on the [Issues](../../issues) tab. Include your WordPress and PHP versions and the steps to reproduce it.
- **Have a question?** Start a thread on the [Discussions](../../discussions) tab.

### Contributing code

Code contributions are welcome. The short version:

1. Fork the repository and clone your fork.
2. Create a branch with a clear name, like `feature/short-descriptive-name`.
3. Make your change and test it against the edge cases.
4. Run the coding-standards check before you open the pull request.
5. Open a pull request that explains what changed and why.

The full workflow and standards live in [CONTRIBUTING.md](CONTRIBUTING.md). Contributing is never required, but it is always appreciated.

## About This Is My URL

This plugin is built and maintained by [This Is My URL](https://thisismyurl.com/), the WordPress development and technical SEO practice of Christopher Ross. I help teams build WordPress sites that stay secure, fast, and maintainable, and I write small, focused plugins like this one for the problems those sites keep running into.

### My background

- On the web since 1996, and in WordPress since 2007
- WordPress.org plugin developer with 19 plugins published since 2009
- Technical SEO practitioner focused on performance, security, and search visibility
- Lead instructor and curriculum architect at the M.L. Campbell Training Center, the Sherwin-Williams® international training facility for its industrial wood division

### Ways to connect

- **Website:** [thisismyurl.com](https://thisismyurl.com/)
- **WordPress.org:** [profiles.wordpress.org/thisismyurl](https://profiles.wordpress.org/thisismyurl/)
- **GitHub:** [github.com/thisismyurl](https://github.com/thisismyurl)
- **LinkedIn:** [linkedin.com/in/thisismyurl](https://linkedin.com/in/thisismyurl)

## Contributors

- **Christopher Ross** ([@thisismyurl](https://github.com/thisismyurl)) — original author (2008), current maintainer
- **Phill Coxon** — maintainer, 2016–2019
- Thanks to everyone who has reported issues and tested edge cases

## License

GPL-2.0-or-later — see [LICENSE](LICENSE) or [gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html). The original copyright remains with Christopher Ross (2008); maintenance contributions between 2016 and 2019 are credited to Phill Coxon.

---
*This project follows the [10 Core Pillars](PILLARS.md). Support quality work [here](https://github.com/sponsors/thisismyurl).*
