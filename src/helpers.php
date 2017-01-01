<?php

/*
 * NOTICE OF LICENSE
 *
 * Part of the Rinvex Language Package.
 *
 * This source file is subject to The MIT License (MIT)
 * that is bundled with this package in the LICENSE file.
 *
 * Package: Rinvex Language Package
 * License: The MIT License (MIT)
 * Link:    https://rinvex.com
 */

declare(strict_types=1);

use Rinvex\Language\LanguageLoader;

if (! function_exists('language')) {
    /**
     * Get the language by it's ISO 639-1.
     *
     * @param string $code
     * @param bool   $hydrate
     *
     * @return \Rinvex\Language\Language|array
     */
    function language($code, $hydrate = true)
    {
        return LanguageLoader::language($code, $hydrate);
    }
}

if (! function_exists('languages')) {
    /**
     * Get all languages.
     *
     * @param bool $hydrate
     *
     * @return array
     */
    function languages($hydrate = false)
    {
        return LanguageLoader::languages($hydrate);
    }
}

if (! function_exists('language_scripts')) {
    /**
     * Get all language scripts.
     *
     * @return array
     */
    function language_scripts()
    {
        return LanguageLoader::scripts();
    }
}

if (! function_exists('language_families')) {
    /**
     * Get all language families.
     *
     * @return array
     */
    function language_families()
    {
        return LanguageLoader::families();
    }
}
