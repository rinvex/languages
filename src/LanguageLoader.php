<?php

declare(strict_types=1);

namespace Rinvex\Language;

use Closure;

class LanguageLoader
{
    /**
     * The languages array.
     *
     * @var array
     */
    protected static $languages;

    /**
     * Get the language by it's ISO ISO 639-1 code.
     *
     * @param string $code
     * @param bool   $hydrate
     *
     * @throws \Rinvex\Language\LanguageLoaderException
     *
     * @return \Rinvex\Language\Language|array
     */
    public static function language($code, $hydrate = true)
    {
        $code = mb_strtolower($code);

        if (! isset(static::$languages)) {
            static::$languages = json_decode(static::getFile(__DIR__.'/../resources/languages.json'), true);
        }

        if (! isset(static::$languages[$code])) {
            throw LanguageLoaderException::invalidLanguage();
        }

        return $hydrate ? new Language(static::$languages[$code]) : static::$languages[$code];
    }

    /**
     * Get all languages.
     *
     * @param bool $hydrate
     *
     * @return array
     */
    public static function languages($hydrate = false)
    {
        if (! isset(static::$languages)) {
            static::$languages = json_decode(static::getFile(__DIR__.'/../resources/languages.json'), true);
        }

        return $hydrate ? array_map(function ($language) {
            return new Language($language);
        }, static::$languages) : static::$languages;
    }

    /**
     * Get all language scripts.
     *
     * @return array
     */
    public static function scripts()
    {
        if (! isset(static::$languages)) {
            static::$languages = json_decode(static::getFile(__DIR__.'/../resources/languages.json'), true);
        }

        return static::pluck(static::$languages, 'script.name', 'script.iso_15924');
    }

    /**
     * Get all language families.
     *
     * @return array
     */
    public static function families()
    {
        if (! isset(static::$languages)) {
            static::$languages = json_decode(static::getFile(__DIR__.'/../resources/languages.json'), true);
        }

        return static::pluck(static::$languages, 'family.name', 'family.iso_639_5');
    }

    /**
     * Filter items by the given key value pair.
     *
     * @param string $key
     * @param mixed  $operator
     * @param mixed  $value
     *
     * @return array
     */
    public static function where($key, $operator, $value = null)
    {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }

        if (! isset(static::$languages)) {
            static::$languages = json_decode(static::getFile(__DIR__.'/../resources/languages.json'), true);
        }

        return static::filter(static::$languages, static::operatorForWhere($key, $operator, $value));
    }

    /**
     * Get an operator checker callback.
     *
     * @param string $key
     * @param string $operator
     * @param mixed  $value
     *
     * @return \Closure
     */
    protected static function operatorForWhere($key, $operator, $value)
    {
        return function ($item) use ($key, $operator, $value) {
            $retrieved = static::get($item, $key);

            switch ($operator) {
                default:
                case '=':
                case '==':  return $retrieved === $value;
                case '!=':
                case '<>':  return $retrieved !== $value;
                case '<':   return $retrieved < $value;
                case '>':   return $retrieved > $value;
                case '<=':  return $retrieved <= $value;
                case '>=':  return $retrieved >= $value;
                case '===': return $retrieved === $value;
                case '!==': return $retrieved !== $value;
            }
        };
    }

    /**
     * Run a filter over each of the items.
     *
     * @param array         $items
     * @param callable|null $callback
     *
     * @return array
     */
    protected static function filter($items, callable $callback = null)
    {
        if ($callback) {
            return array_filter($items, $callback, ARRAY_FILTER_USE_BOTH);
        }

        return array_filter($items);
    }

    /**
     * Get an item from an array or object using "dot" notation.
     *
     * @param mixed        $target
     * @param string|array $key
     * @param mixed        $default
     *
     * @return mixed
     */
    protected static function get($target, $key, $default = null)
    {
        if (is_null($key)) {
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', $key);

        while (($segment = array_shift($key)) !== null) {
            if ($segment === '*') {
                if (! is_array($target)) {
                    return $default instanceof Closure ? $default() : $default;
                }

                $result = static::pluck($target, $key);

                return in_array('*', $key) ? static::collapse($result) : $result;
            }

            if (is_array($target) && array_key_exists($segment, $target)) {
                $target = $target[$segment];
            } elseif (is_object($target) && isset($target->{$segment})) {
                $target = $target->{$segment};
            } else {
                return $default instanceof Closure ? $default() : $default;
            }
        }

        return $target;
    }

    /**
     * Pluck an array of values from an array.
     *
     * @param array             $array
     * @param string|array      $value
     * @param string|array|null $key
     *
     * @return array
     */
    protected static function pluck($array, $value, $key = null)
    {
        $results = [];

        $value = is_string($value) ? explode('.', $value) : $value;

        $key = is_null($key) || is_array($key) ? $key : explode('.', $key);

        foreach ($array as $item) {
            $itemValue = static::get($item, $value);

            // If the key is "null", we will just append the value to the array and keep
            // looping. Otherwise we will key the array using the value of the key we
            // received from the developer. Then we'll return the final array form.
            if (is_null($key)) {
                $results[] = $itemValue;
            } else {
                $itemKey = static::get($item, $key);

                $results[$itemKey] = $itemValue;
            }
        }

        return $results;
    }

    /**
     * Collapse an array of arrays into a single array.
     *
     * @param array $array
     *
     * @return array
     */
    protected static function collapse($array)
    {
        $results = [];

        foreach ($array as $values) {
            if (! is_array($values)) {
                continue;
            }

            $results = array_merge($results, $values);
        }

        return $results;
    }

    /**
     * Get contents of the given file path.
     *
     * @param string $filePath
     *
     * @throws \Rinvex\Language\LanguageLoaderException
     *
     * @return string
     */
    public static function getFile($filePath)
    {
        if (! file_exists($filePath)) {
            throw LanguageLoaderException::invalidLanguage();
        }

        return file_get_contents($filePath);
    }
}
