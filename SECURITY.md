# Security policy

## Supported versions

Only the latest released version of Auto Copyright receives security fixes. Older releases (the `2.x` and `14.x` lineage) are no longer maintained.

| Version | Supported          |
|---------|--------------------|
| 0.6xxx  | Yes                |
| 14.x    | No                 |
| 2.x     | No                 |

## Reporting a vulnerability

Please **do not** open a public GitHub issue for security findings.

Email security reports to **security@thisismyurl.com** with:

- A description of the issue and its impact.
- Steps to reproduce, ideally with the smallest possible WordPress install.
- The plugin version and PHP / WordPress versions you tested against.
- Any proof-of-concept code (gist or attached file).

You will get an acknowledgement within 5 business days. Disclosure timeline is coordinated case by case; once a fix ships you'll be credited in the changelog unless you ask otherwise.

## Scope

In scope:

- The PHP source in `auto-copyright-1.php`.
- The widget output and shortcodes (`[auto_copyright]`, `[thisismyurl_autocopyright_article]`).
- The transient cache invalidation hooks.

Out of scope:

- Issues that require an attacker to already have `manage_options` or `edit_theme_options` capability — the WordPress trust model assumes those users.
- Reports against the `2.x` / `14.x` legacy releases.
