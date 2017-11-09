# Rinvex Language

**Rinvex Language** is a simple and lightweight package for retrieving language details with flexibility. A whole bunch of data including name, native, iso codes, language family, language script, language cultures, and other attributes for the 180+ known languages worldwide at your fingertips.

[![Packagist](https://img.shields.io/packagist/v/rinvex/language.svg?label=Packagist&style=flat-square)](https://packagist.org/packages/rinvex/language)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/rinvex/language.svg?label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/rinvex/language/)
[![Code Climate](https://img.shields.io/codeclimate/github/rinvex/language.svg?label=CodeClimate&style=flat-square)](https://codeclimate.com/github/rinvex/language)
[![Travis](https://img.shields.io/travis/rinvex/language.svg?label=TravisCI&style=flat-square)](https://travis-ci.org/rinvex/language)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/4505ee8a-52fc-4229-ae93-7e4f2523bda9.svg?label=SensioLabs&style=flat-square)](https://insight.sensiolabs.com/projects/4505ee8a-52fc-4229-ae93-7e4f2523bda9)
[![StyleCI](https://styleci.io/repos/77772990/shield)](https://styleci.io/repos/77772990)
[![License](https://img.shields.io/packagist/l/rinvex/language.svg?label=License&style=flat-square)](https://github.com/rinvex/language/blob/develop/LICENSE)


## Usage

Install via `composer require rinvex/language`, then use intuitively:
```php
// Get single language
$english = language('en');

// Get language name: English
echo $english->getName();

// Get language native name: English
echo $english->getNativeName();

// Get language ISO 639-1 code: en
echo $english->getIso6391();

// Get language ISO 639-2 code: eng
echo $english->getIso6392();

// Get language ISO 639-3 code: eng
echo $english->getIso6393();

// Get language script details: {"name": "Latin","iso_15924": "Latn","iso_numeric": "215","direction": "ltr"}
echo $english->getScript();

// Get language script name: Latin
echo $english->getScriptName();

// Get language script ISO 15924 code: Latn
echo $english->getScriptIso15924();

// Get language script ISO numeric code: 215
echo $english->getScriptIsoNumeric();

// Get language script direction: ltr
echo $english->getScriptDirection();

// Get language family details: {"name": "Indo-European","iso_639_5": "ine","hierarchy": "ine"}
echo $english->getFamily();

// Get language family name: Indo-European
echo $english->getFamilyName();

// Get language family ISO 6395 code: ine
echo $english->getFamilyIso6395();

// Get language family hierarchy: ine
echo $english->getFamilyHierarchy();

// Get language scope: individual
echo $english->getScope();

// Get language type: living
echo $english->getType();

// Get language cultures: {"en-US": {"name": "English (United States)","native": "English (United States)"}, {...}}
echo $english->getCultures();

// Get language specific culture: {"name": "English (United States)","native": "English (United States)"}
echo $english->getCulture('en-US');


// Get all languages
$languages = languages();

// Get all language scripts
$language_scripts = language_scripts();

// Get all language families
$language_families = language_families();

// Get languages with where condition (language script: Latin)
$whereLanguages = \Rinvex\Language\LanguageLoader::where('script.name', 'Latin');
```

> **Notes:**
> - **Rinvex Language** is framework-agnostic, so it's compatible with any PHP framework whatsoever without any dependencies at all, except for the PHP version itself **^7.0.0**. Awesome, huh? :smiley:
> - **Rinvex Language** provides the global helpers for your convenience and for ease of use, but in fact it's just wrappers around the underlying `LanguageLoader` class, which you can utilize and use directly if you wish


## Features Explained

- Language data are all stored here: `resources/languages.json`.
- `name` - language english name
- `native` - language native name
- `iso_639_1` - two letter code ISO 639-1
- `iso_639_2` - three letter code ISO 639-2
- `iso_639_3` - three letter code ISO 639-3
- `script` - language script details
    - name: language script name
    - iso_15924: language script ISO 15924 code
    - iso_numeric: language script ISO numeric code
    - direction: language script writing direction
- `family` - language family details
    - name: language family name
    - iso_639_5: three-letter ISO 639-5 code
    - hierarchy: language family hierarchy
- `cultures` - list of language cultures
    - key: four-letter language culture code (iso_639_1-iso_3166_1)
    - value: culture object
        - name: language culture name
        - native: language culture native name
- `scope` - language scope (like individual or macrolanguage)
- `type` - language type (like living or ancient)


## Changelog

Refer to the [Changelog](CHANGELOG.md) for a full history of the project.


## Support

The following support channels are available at your fingertips:

- [Chat on Slack](http://chat.rinvex.com)
- [Help on Email](mailto:help@rinvex.com)
- [Follow on Twitter](https://twitter.com/rinvex)


## Contributing & Protocols

Thank you for considering contributing to this project! The contribution guide can be found in [CONTRIBUTING.md](CONTRIBUTING.md).

Bug reports, feature requests, and pull requests are very welcome.

- [Versioning](CONTRIBUTING.md#versioning)
- [Pull Requests](CONTRIBUTING.md#pull-requests)
- [Coding Standards](CONTRIBUTING.md#coding-standards)
- [Feature Requests](CONTRIBUTING.md#feature-requests)
- [Git Flow](CONTRIBUTING.md#git-flow)


## Security Vulnerabilities

If you discover a security vulnerability within this project, please send an e-mail to [help@rinvex.com](help@rinvex.com). All security vulnerabilities will be promptly addressed.


## About Rinvex

Rinvex is a software solutions startup, specialized in integrated enterprise solutions for SMEs established in Alexandria, Egypt since June 2016. We believe that our drive The Value, The Reach, and The Impact is what differentiates us and unleash the endless possibilities of our philosophy through the power of software. We like to call it Innovation At The Speed Of Life. That’s how we do our share of advancing humanity.


## License

This software is released under [The MIT License (MIT)](LICENSE).

(c) 2016-2018 Rinvex LLC, Some rights reserved.
