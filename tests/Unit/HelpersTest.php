<?php

declare(strict_types=1);

namespace Rinvex\Language\Tests\Unit;

use Rinvex\Language\Language;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class HelpersTest extends TestCase
{
    #[Test]
    public function it_returns_language_data_through_helper(): void
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

    #[Test]
    public function it_returns_languages_array_through_helper(): void
    {
        $this->assertEquals(183, count(languages()));
        $this->assertIsArray(languages()['en']);
        $this->assertEquals('English', languages()['en']['name']);
    }

    #[Test]
    public function it_returns_language_scripts_array_through_helper(): void
    {
        $this->assertEquals(29, count(language_scripts()));
        $this->assertIsArray(language_scripts());
        $this->assertArrayHasKey('Arab', language_scripts());
    }

    #[Test]
    public function it_returns_language_families_array_through_helper(): void
    {
        $this->assertEquals(27, count(language_families()));
        $this->assertIsArray(language_families());
        $this->assertArrayHasKey('afa', language_families());
    }
}
