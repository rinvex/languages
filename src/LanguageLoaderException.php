<?php

declare(strict_types=1);

namespace Rinvex\Language;

use Exception;

class LanguageLoaderException extends Exception
{
    /**
     * Create a new exception instance.
     */
    public static function invalidLanguage(): static
    {
        return new static('Language code may be misspelled, invalid, or data not found on server!');
    }
}
