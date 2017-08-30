<?php

declare(strict_types=1);

namespace Rinvex\Language;

use Exception;

class Language
{
    /**
     * The attributes array.
     *
     * @var array
     */
    protected $attributes;

    /**
     * Create a new Language instance.
     *
     * @param array $attributes
     *
     * @throws \Exception
     */
    public function __construct($attributes)
    {
        // Set the attributes
        $this->setAttributes($attributes);

        // Check required mandatory attributes
        if (empty($this->getName()) || empty($this->getNativeName()) || empty($this->getIso6391())) {
            throw new Exception('Missing mandatory language attributes!');
        }
    }

    /**
     * Set the attributes.
     *
     * @param array $attributes
     *
     * @return $this
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Get the attributes.
     *
     * @return array|null
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set single attribute.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Get an item from attributes array using "dot" notation.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $array = $this->attributes;

        if (is_null($key)) {
            return $array;
        }

        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    /**
     * Get the name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->get('name');
    }

    /**
     * Get the given native name or fallback to first native name.
     *
     * @return string|null
     */
    public function getNativeName()
    {
        return $this->get('native');
    }

    /**
     * Get the ISO 639-1 code.
     *
     * @return string|null
     */
    public function getIso6391()
    {
        return $this->get('iso_639_1');
    }

    /**
     * Get the ISO 639-2 code.
     *
     * @return string|null
     */
    public function getIso6392()
    {
        return $this->get('iso_639_2');
    }

    /**
     * Get the ISO 639-3 code.
     *
     * @return string|null
     */
    public function getIso6393()
    {
        return $this->get('iso_639_3');
    }

    /**
     * Get the script.
     *
     * @return array|null
     */
    public function getScript()
    {
        return $this->get('script');
    }

    /**
     * Get the script name.
     *
     * @return string|null
     */
    public function getScriptName()
    {
        return $this->get('script.name');
    }

    /**
     * Get the script ISO 15924.
     *
     * @return string|null
     */
    public function getScriptIso15924()
    {
        return $this->get('script.iso_15924');
    }

    /**
     * Get the script ISO numeric.
     *
     * @return string|null
     */
    public function getScriptIsoNumeric()
    {
        return $this->get('script.iso_numeric');
    }

    /**
     * Get the script direction.
     *
     * @return string|null
     */
    public function getScriptDirection()
    {
        return $this->get('script.direction');
    }

    /**
     * Get the family.
     *
     * @return array|null
     */
    public function getFamily()
    {
        return $this->get('family');
    }

    /**
     * Get the family name.
     *
     * @return string|null
     */
    public function getFamilyName()
    {
        return $this->get('family.name');
    }

    /**
     * Get the family ISO 639-5.
     *
     * @return string|null
     */
    public function getFamilyIso6395()
    {
        return $this->get('family.iso_639_5');
    }

    /**
     * Get the family hierarchy.
     *
     * @return string|null
     */
    public function getFamilyHierarchy()
    {
        return $this->get('family.hierarchy');
    }

    /**
     * Get the scope.
     *
     * @return string|null
     */
    public function getScope()
    {
        return $this->get('scope');
    }

    /**
     * Get the type.
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->get('type');
    }

    /**
     * Get the cultures.
     *
     * @return array|null
     */
    public function getCultures()
    {
        return $this->get('cultures');
    }

    /**
     * Get the given culture.
     *
     * @param string|null $culture
     *
     * @return array|null
     */
    public function getCulture($culture = null)
    {
        return $this->getCultures()[$culture] ?? (! empty($this->getCultures()) ? current($this->getCultures()) : null);
    }
}
