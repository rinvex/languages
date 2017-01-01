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

namespace Rinvex\Language;

use Exception;

class LanguageLoaderException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @return static
     */
    public static function invalidLanguage()
    {
        return new static('Language code may be misspelled, invalid, or data not found on server!');
    }
}
