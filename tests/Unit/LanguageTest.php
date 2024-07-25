<?php

declare(strict_types=1);

namespace Rinvex\Language\Tests\Unit;

use Exception;
use Rinvex\Language\Language;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class LanguageTest extends TestCase
{
    /** @var array */
    protected $languageArray;

    /** @var Language */
    protected $languageObject;

    protected function setUp(): void
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

    #[Test]
    public function it_throws_an_exception_when_missing_mandatory_attributes(): void
    {
        $this->expectException(Exception::class);

        new Language([]);
    }

    #[Test]
    public function it_sets_attributes_once_instantiated(): void
    {
        $this->assertEquals($this->languageArray['name'], $this->languageObject->getName());
        $this->assertEquals($this->languageArray['native'], $this->languageObject->getNativeName());
        $this->assertEquals($this->languageArray['iso_639_1'], $this->languageObject->getIso6391());
    }

    #[Test]
    public function it_gets_attributes(): void
    {
        $this->assertEquals($this->languageArray, $this->languageObject->getAttributes());
    }

    #[Test]
    public function it_sets_attributes(): void
    {
        $this->languageObject->setAttributes(['iso_639_3' => 'cha']);

        $this->assertEquals('cha', $this->languageObject->getIso6393());
    }

    #[Test]
    public function it_gets_dotted_attribute(): void
    {
        $this->assertEquals($this->languageArray['script']['iso_15924'], $this->languageObject->get('script.iso_15924'));
    }

    #[Test]
    public function it_gets_default_when_missing_value(): void
    {
        $this->assertEquals('default', $this->languageObject->get('unknown', 'default'));
    }

    #[Test]
    public function it_gets_all_attributes_when_missing_key(): void
    {
        $this->assertEquals($this->languageArray, $this->languageObject->get(null));
    }

    #[Test]
    public function it_sets_attribute(): void
    {
        $this->languageObject->set('iso_639_3', 'cha');

        $this->assertEquals('cha', $this->languageObject->getIso6393());
    }

    #[Test]
    public function its_fluently_chainable_when_sets_attributes(): void
    {
        $this->assertEquals($this->languageObject, $this->languageObject->setAttributes([]));
    }

    #[Test]
    public function it_returns_name(): void
    {
        $this->assertEquals($this->languageArray['name'], $this->languageObject->getName());
    }

    #[Test]
    public function it_returns_null_when_missing_name(): void
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getName());
    }

    #[Test]
    public function it_returns_native_name(): void
    {
        $this->assertEquals($this->languageArray['native'], $this->languageObject->getNativeName());
    }

    #[Test]
    public function it_returns_null_when_missing_native_name(): void
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getNativeName());
    }

    #[Test]
    public function it_returns_iso6391(): void
    {
        $this->assertEquals($this->languageArray['iso_639_1'], $this->languageObject->getIso6391());
    }

    #[Test]
    public function it_returns_null_when_missing_iso6391(): void
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getIso6391());
    }

    #[Test]
    public function it_returns_iso6392(): void
    {
        $this->assertEquals($this->languageArray['iso_639_2'], $this->languageObject->getIso6392());
    }

    #[Test]
    public function it_returns_null_when_missing_iso6392(): void
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getIso6392());
    }

    #[Test]
    public function it_returns_iso6393(): void
    {
        $this->assertEquals($this->languageArray['iso_639_3'], $this->languageObject->getIso6393());
    }

    #[Test]
    public function it_returns_null_when_missing_iso6393(): void
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getIso6393());
    }

    #[Test]
    public function it_returns_script(): void
    {
        $this->assertEquals($this->languageArray['script'], $this->languageObject->getScript());
    }

    #[Test]
    public function it_returns_null_when_missing_script(): void
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getScript());
    }

    #[Test]
    public function it_returns_script_name(): void
    {
        $this->assertEquals($this->languageArray['script']['name'], $this->languageObject->getScriptName());
    }

    #[Test]
    public function it_returns_null_when_missing_script_name(): void
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getScriptName());
    }

    #[Test]
    public function it_returns_script_iso_15924(): void
    {
        $this->assertEquals($this->languageArray['script']['iso_15924'], $this->languageObject->getScriptIso15924());
    }

    #[Test]
    public function it_returns_null_when_missing_script_iso_15924(): void
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getScriptIso15924());
    }

    #[Test]
    public function it_returns_script_iso_numeric(): void
    {
        $this->assertEquals($this->languageArray['script']['iso_numeric'], $this->languageObject->getScriptIsoNumeric());
    }

    #[Test]
    public function it_returns_null_when_missing_script_iso_numeric(): void
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getScriptIsoNumeric());
    }

    #[Test]
    public function it_returns_script_direction(): void
    {
        $this->assertEquals($this->languageArray['script']['direction'], $this->languageObject->getScriptDirection());
    }

    #[Test]
    public function it_returns_null_when_missing_script_direction(): void
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getScriptDirection());
    }

    #[Test]
    public function it_returns_family(): void
    {
        $this->assertEquals($this->languageArray['family'], $this->languageObject->getFamily());
    }

    #[Test]
    public function it_returns_null_when_missing_family(): void
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getFamily());
    }

    #[Test]
    public function it_returns_family_name(): void
    {
        $this->assertEquals($this->languageArray['family']['name'], $this->languageObject->getFamilyName());
    }

    #[Test]
    public function it_returns_null_when_missing_family_name(): void
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getFamilyName());
    }

    #[Test]
    public function it_returns_family_iso_639_5(): void
    {
        $this->assertEquals($this->languageArray['family']['iso_639_5'], $this->languageObject->getFamilyIso6395());
    }

    #[Test]
    public function it_returns_null_when_missing_family_iso_639_5(): void
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getFamilyIso6395());
    }

    #[Test]
    public function it_returns_family_hierarchy(): void
    {
        $this->assertEquals($this->languageArray['family']['hierarchy'], $this->languageObject->getFamilyHierarchy());
    }

    #[Test]
    public function it_returns_null_when_missing_family_hierarchy(): void
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getFamilyHierarchy());
    }

    #[Test]
    public function it_returns_scope(): void
    {
        $this->assertEquals($this->languageArray['scope'], $this->languageObject->getScope());
    }

    #[Test]
    public function it_returns_null_when_missing_scope(): void
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getScope());
    }

    #[Test]
    public function it_returns_type(): void
    {
        $this->assertEquals($this->languageArray['type'], $this->languageObject->getType());
    }

    #[Test]
    public function it_returns_null_when_missing_type(): void
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getType());
    }

    #[Test]
    public function it_returns_cultures(): void
    {
        $this->assertEquals($this->languageArray['cultures'], $this->languageObject->getCultures());
    }

    #[Test]
    public function it_returns_null_when_missing_cultures(): void
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getCultures());
    }

    #[Test]
    public function it_returns_culture(): void
    {
        $this->assertEquals($this->languageArray['cultures']['am-ET'], $this->languageObject->getCulture('am-ET'));
    }

    #[Test]
    public function it_returns_null_when_missing_culture(): void
    {
        $this->languageObject->setAttributes([]);

        $this->assertNull($this->languageObject->getCulture('am-ET'));
    }
}
