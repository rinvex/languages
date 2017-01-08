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

namespace Rinvex\Language\Test;

use Rinvex\Language\Language;
use PHPUnit_Framework_TestCase;

class HelpersTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_returns_language_data_through_helper()
    {
        $amharic = [
            'name' => 'Amharic',
            'native' => 'አማርኛ',
            'iso_639_1' => 'am',
            'iso_639_2' => 'amh',
            'iso_639_3' => 'amh',
            'script' => [
                'name' => 'Ethiopic (Ge_ez)',
                'iso_15924' => 'Ethi',
                'iso_numeric' => '430',
                'direction' => 'ltr',
            ],
            'family' => [
                'name' => 'Afro-Asiatic',
                'iso_639_5' => 'afa',
                'hierarchy' => 'afa',
            ],
            'cultures' => [
                'am-ET' => [
                    'name' => 'Amharic (Ethiopia)',
                    'native' => 'አማርኛ (ኢትዮጵያ)',
                ],
            ],
            'scope' => 'individual',
            'type' => 'living',
        ];

        $this->assertEquals($amharic, language('am', false));
        $this->assertEquals(new Language($amharic), language('am'));
    }

    /** @test */
    public function it_returns_languages_array_through_helper()
    {
        $this->assertEquals(183, count(languages()));
        $this->assertInternalType('array', languages()['en']);
        $this->assertEquals('English', languages()['en']['name']);
    }

    /** @test */
    public function it_returns_language_scripts_array_through_helper()
    {
        $this->assertEquals(29, count(language_scripts()));
        $this->assertInternalType('array', language_scripts());
        $this->assertArrayHasKey('Arab', language_scripts());
    }

    /** @test */
    public function it_returns_language_families_array_through_helper()
    {
        $this->assertEquals(27, count(language_families()));
        $this->assertInternalType('array', language_families());
        $this->assertArrayHasKey('afa', language_families());
    }
}
