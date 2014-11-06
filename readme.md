## Fallback Locales ##

**Tags:** l10n, translation, language
**Requires at least:** 4.0
**Tested up to:** 4.0
**Stable tag:** 0.1.0
**License:** GPLv2

## Description ##

If translations are not available for the set language locale, this plugin will attempt to load alternate translation locales in the same language before falling back to English. For example, if the language locale is es_MX (Spanish - Mexico) and no translations are available, WordPress will look for other Spanish translations (such es_ES, etc.) before displaying the default language of the theme or plugin.

## Todo ##

* Add button to clear transient cache
* Clear transient automatically on plugin/theme update
* Test with language packs
* Test multisite
* Delete cache values on settings update

## Changelog ##

0.1.0
---

* Initial release on GitHub