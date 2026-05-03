# Contributing to Auto Copyright

Thanks for considering a contribution. This plugin is small (one PHP file), so the contribution loop is short.

## Reporting issues

- Use the GitHub issue templates under `.github/ISSUE_TEMPLATE/`.
- Include WordPress version, PHP version, and active theme. Most reports against this plugin trace back to a theme calling the function tag in an unusual context (outside the Loop, before `init`, etc.).
- For security issues, see `SECURITY.md` — do **not** open a public issue.

## Pull requests

- Fork, branch from `main`, open the PR against `main`.
- Keep PRs scoped: one logical change per PR is easier to review and ship.
- Match the existing code style — WordPress Coding Standards, tabs for indent, Yoda conditions, snake_case for functions.
- Run `php -l auto-copyright-1.php` before pushing. CI will run the same check across PHP 7.4 / 8.0 / 8.1 / 8.2 / 8.3.
- If you add user-facing strings, wrap them in the `auto-copyright-1` text domain.
- Update `CHANGELOG.md` with a one-line entry under an `Unreleased` section.

## Local testing

There is no full unit-test suite yet. The minimum smoke check before opening a PR:

1. Install the plugin in a fresh WordPress.
2. Confirm `[auto_copyright]` and `[thisismyurl_autocopyright_article]` both render in a post.
3. Add the **Auto Copyright** widget to a sidebar; confirm Title and Format inputs persist across save.

A `tests/` scaffold exists for the year-span calculation; expand it as new behaviour lands.

## Versioning

The project uses `x.Yddd`:

- `x` = release class (`0` = pre-release, `1` = full).
- `Y` = last digit of the year (e.g. `6` for 2026).
- `ddd` = Julian day of year (`001`–`366`).

Default to pre-release (`x = 0`) unless a maintainer explicitly tags otherwise.

## License

By contributing, you agree your contribution is licensed under GPL-2.0-or-later, the same license as the plugin.
