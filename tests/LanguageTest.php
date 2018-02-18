<?php

declare(strict_types=1);

namespace Rinvex\Language\Tests;

use Exception;
use Rinvex\Language\Language;
use PHPUnit\Framework\TestCase;

class LanguageTest extends TestCase
{
    /** @var array */
    protected $languageArray;

    /** @var \Rinvex\Language\Language */
    protected $languageObject;

    public function setUp()
    {
        parent::setUp();

        $this->languageArray = [
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

        $this->languageObject = new Language($this->languageArray);
    }

    /** @test */
    public function it_throws_an_exception_when_missing_mandatory_attributes()
    {
        $this->expectException(Exception::class);

        new Language([]);
    }

    /** @test */
    public function it_sets_attributes_once_instantiated()
    {
        $this->assertEquals($this->languageArray['name'], $this->languageObject->getName());
        $this->assertEquals($this->languageArray['native'], $this->languageObject->getNativeName());
        $this->assertEquals($this->languageArray['iso_639_1'], $this->languageObject->getIso6391());
    }

    /** @test */
    public function it_gets_attributes()
    {
        $this->assertEquals($this->languageArray, $this->languageObject->getAttributes());
    }

    /** @test */
    public function it_sets_attributes()
    {
        $this->languageObject->setAttributes(['iso_639_3' => 'cha']);

        $this->assertEquals('cha', $this->languageObject->getIso6393());
    }

    /** @test */
    public function it_gets_dotted_attribute()
    {
        $this->assertEquals($this->languageArray['script']['iso_15924'], $this->languageObject->get('script.iso_15924'));
    }

    /** @test */
    public function it_gets_default_when_missing_value()
    {
        $this->assertEquals('default', $this->languageObject->get('unknown', 'default'));
    }

    /** @test */
    public function it_gets_all_attributes_when_missing_key()
    {
        $this->assertEquals($this->languageArray, $this->languageObject->get(null));
    }

    /** @test */
    public function it_sets_attribute()
    {
        $this->languageObject->set('iso_639_3', 'cha');

        $this->assertEquals('cha', $this->languageObject->getIso6393());
    }

    /** @test */
    public function its_fluently_chainable_when_sets_attributes()
    {
        $this->assertEquals($this->languageObject, $this->languageObject->setAttributes([]));
    }

    /** @test */
    public function it_returns_name()
    {
        $this->assertEquals($this->languageArray['name'], $this->languageObject->getName());
    }

    /** @test */
    public function it_returns_null_when_missing_name()
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getName());
    }

    /** @test */
    public function it_returns_native_name()
    {
        $this->assertEquals($this->languageArray['native'], $this->languageObject->getNativeName());
    }

    /** @test */
    public function it_returns_null_when_missing_native_name()
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getNativeName());
    }

    /** @test */
    public function it_returns_iso6391()
    {
        $this->assertEquals($this->languageArray['iso_639_1'], $this->languageObject->getIso6391());
    }

    /** @test */
    public function it_returns_null_when_missing_iso6391()
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getIso6391());
    }

    /** @test */
    public function it_returns_iso6392()
    {
        $this->assertEquals($this->languageArray['iso_639_2'], $this->languageObject->getIso6392());
    }

    /** @test */
    public function it_returns_null_when_missing_iso6392()
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getIso6392());
    }

    /** @test */
    public function it_returns_iso6393()
    {
        $this->assertEquals($this->languageArray['iso_639_3'], $this->languageObject->getIso6393());
    }

    /** @test */
    public function it_returns_null_when_missing_iso6393()
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getIso6393());
    }

    /** @test */
    public function it_returns_script()
    {
        $this->assertEquals($this->languageArray['script'], $this->languageObject->getScript());
    }

    /** @test */
    public function it_returns_null_when_missing_script()
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getScript());
    }

    /** @test */
    public function it_returns_script_name()
    {
        $this->assertEquals($this->languageArray['script']['name'], $this->languageObject->getScriptName());
    }

    /** @test */
    public function it_returns_null_when_missing_script_name()
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getScriptName());
    }

    /** @test */
    public function it_returns_script_iso_15924()
    {
        $this->assertEquals($this->languageArray['script']['iso_15924'], $this->languageObject->getScriptIso15924());
    }

    /** @test */
    public function it_returns_null_when_missing_script_iso_15924()
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getScriptIso15924());
    }

    /** @test */
    public function it_returns_script_iso_numeric()
    {
        $this->assertEquals($this->languageArray['script']['iso_numeric'], $this->languageObject->getScriptIsoNumeric());
    }

    /** @test */
    public function it_returns_null_when_missing_script_iso_numeric()
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getScriptIsoNumeric());
    }

    /** @test */
    public function it_returns_script_direction()
    {
        $this->assertEquals($this->languageArray['script']['direction'], $this->languageObject->getScriptDirection());
    }

    /** @test */
    public function it_returns_null_when_missing_script_direction()
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getScriptDirection());
    }

    /** @test */
    public function it_returns_family()
    {
        $this->assertEquals($this->languageArray['family'], $this->languageObject->getFamily());
    }

    /** @test */
    public function it_returns_null_when_missing_family()
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getFamily());
    }

    /** @test */
    public function it_returns_family_name()
    {
        $this->assertEquals($this->languageArray['family']['name'], $this->languageObject->getFamilyName());
    }

    /** @test */
    public function it_returns_null_when_missing_family_name()
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getFamilyName());
    }

    /** @test */
    public function it_returns_family_iso_639_5()
    {
        $this->assertEquals($this->languageArray['family']['iso_639_5'], $this->languageObject->getFamilyIso6395());
    }

    /** @test */
    public function it_returns_null_when_missing_family_iso_639_5()
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getFamilyIso6395());
    }

    /** @test */
    public function it_returns_family_hierarchy()
    {
        $this->assertEquals($this->languageArray['family']['hierarchy'], $this->languageObject->getFamilyHierarchy());
    }

    /** @test */
    public function it_returns_null_when_missing_family_hierarchy()
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getFamilyHierarchy());
    }

    /** @test */
    public function it_returns_scope()
    {
        $this->assertEquals($this->languageArray['scope'], $this->languageObject->getScope());
    }

    /** @test */
    public function it_returns_null_when_missing_scope()
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getScope());
    }

    /** @test */
    public function it_returns_type()
    {
        $this->assertEquals($this->languageArray['type'], $this->languageObject->getType());
    }

    /** @test */
    public function it_returns_null_when_missing_type()
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getType());
    }

    /** @test */
    public function it_returns_cultures()
    {
        $this->assertEquals($this->languageArray['cultures'], $this->languageObject->getCultures());
    }

    /** @test */
    public function it_returns_null_when_missing_cultures()
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getCultures());
    }

    /** @test */
    public function it_returns_culture()
    {
        $this->assertEquals($this->languageArray['cultures']['am-ET'], $this->languageObject->getCulture('am-ET'));
    }

    /** @test */
    public function it_returns_null_when_missing_culture()
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getCulture('am-ET'));
    }
}
