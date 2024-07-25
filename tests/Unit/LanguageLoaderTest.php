<?php

declare(strict_types=1);

namespace Rinvex\Language\Tests\Unit;

use ReflectionClass;
use Rinvex\Language\Language;
use PHPUnit\Framework\TestCase;
use Rinvex\Language\LanguageLoader;
use PHPUnit\Framework\Attributes\Test;
use Rinvex\Language\LanguageLoaderException;

class LanguageLoaderTest extends TestCase
{
    /** @var array */
    protected static $methods;

    public static function setUpBeforeClass(): void
    {
        $reflectionClass = new ReflectionClass(LanguageLoader::class);
        self::$methods['get'] = $reflectionClass->getMethod('get');
        self::$methods['pluck'] = $reflectionClass->getMethod('pluck');
        self::$methods['filter'] = $reflectionClass->getMethod('filter');
        self::$methods['getFile'] = $reflectionClass->getMethod('getFile');
        self::$methods['collapse'] = $reflectionClass->getMethod('collapse');

        foreach (self::$methods as $method) {
            $method->setAccessible(true);
        }
    }

    public static function tearDownAfterClass(): void
    {
        self::$methods = null;
    }

    #[Test]
    public function it_returns_language_data(): void
    {
        $languageArray = [
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

        $this->assertEquals($languageArray, LanguageLoader::language('am', false));
        $this->assertEquals(new Language($languageArray), LanguageLoader::language('am'));
    }

    #[Test]
    public function it_gets_data_with_where_conditions(): void
    {
        $this->assertEquals(['ar', 'fa', 'ks', 'ku', 'ps', 'sd', 'ug', 'ur'], array_keys(LanguageLoader::where('script.name', 'Arabic')));
        $this->assertEquals('Arabic', current(LanguageLoader::where('native', '=', 'العربية'))['name']);
        $this->assertEquals('Arabic', current(LanguageLoader::where('native', '==', 'العربية'))['name']);
        $this->assertEquals('Arabic', current(LanguageLoader::where('native', '===', 'العربية'))['name']);
        $this->assertEquals('Arabic', current(LanguageLoader::where('native', 'invalid-operator', 'العربية'))['name']);
        $this->assertEquals(['ii', 'zh'], array_keys(LanguageLoader::where('script.iso_numeric', '>', 450)));
        $this->assertEquals(['zh'], array_keys(LanguageLoader::where('script.iso_numeric', '>=', 500)));
        $this->assertEquals(['ar', 'fa', 'he', 'ks', 'ku', 'ps', 'sd', 'ug', 'ur', 'yi'], array_keys(LanguageLoader::where('script.iso_numeric', '<=', 160)));
        $this->assertEquals(64, count(array_keys(LanguageLoader::where('script.name', '<>', 'Latin'))));
        $this->assertEquals(109, count(array_keys(LanguageLoader::where('family.iso_639_5', '!=', 'ine'))));
        $this->assertEquals(10, count(array_keys(LanguageLoader::where('type', '!==', 'living'))));
        $this->assertEquals(2, count(array_keys(LanguageLoader::where('script.iso_numeric', '<', 130))));
    }

    #[Test]
    public function it_returns_languages_array(): void
    {
        $this->assertEquals(183, count(LanguageLoader::languages()));
        $this->assertIsArray(LanguageLoader::languages()['am']);
        $this->assertEquals('English', LanguageLoader::languages()['en']['name']);
    }

    #[Test]
    public function it_returns_language_scripts_array(): void
    {
        $this->assertEquals(29, count(LanguageLoader::scripts()));
        $this->assertIsArray(LanguageLoader::scripts());
        $this->assertArrayHasKey('Arab', LanguageLoader::scripts());
    }

    #[Test]
    public function it_returns_language_families_array(): void
    {
        $this->assertEquals(27, count(LanguageLoader::families()));
        $this->assertIsArray(LanguageLoader::families());
        $this->assertArrayHasKey('afa', LanguageLoader::families());
    }

    #[Test]
    public function it_returns_language_hydrated(): void
    {
        $this->assertEquals(183, count(LanguageLoader::languages(true)));
        $this->assertIsObject(LanguageLoader::languages(true)['en']);
        $this->assertEquals('English', LanguageLoader::languages(true)['en']->getName());
    }

    #[Test]
    public function it_throws_an_exception_when_invalid_language(): void
    {
        $this->expectException(LanguageLoaderException::class);

        LanguageLoader::language('asd');
    }

    #[Test]
    public function it_filters_data(): void
    {
        $array1 = [['id' => 1, 'name' => 'Hello'], ['id' => 2, 'name' => 'World']];
        $this->assertEquals([1 => ['id' => 2, 'name' => 'World']], self::$methods['filter']->invoke(null, $array1, fn ($item): bool => $item['id'] === 2));

        $array2 = ['', 'Hello', '', 'World'];
        $this->assertEquals(['Hello', 'World'], array_values(self::$methods['filter']->invoke(null, $array2)));

        $array3 = ['id' => 1, 'first' => 'Hello', 'second' => 'World'];
        $this->assertEquals(['first' => 'Hello', 'second' => 'World'], self::$methods['filter']->invoke(null, $array3, fn ($item, $key): bool => $key !== 'id'));
    }

    #[Test]
    public function it_gets_data(): void
    {
        $object = (object) ['users' => ['name' => ['Taylor', 'Otwell']]];
        $array = [(object) ['users' => [(object) ['name' => 'Taylor']]]];
        $dottedArray = ['users' => ['first.name' => 'Taylor', 'middle.name' => null]];
        $this->assertEquals('Taylor', self::$methods['get']->invoke(null, $object, 'users.name.0'));
        $this->assertEquals('Taylor', self::$methods['get']->invoke(null, $array, '0.users.0.name'));
        $this->assertNull(self::$methods['get']->invoke(null, $array, '0.users.3'));
        $this->assertEquals('Not found', self::$methods['get']->invoke(null, $array, '0.users.3', 'Not found'));
        $this->assertEquals('Not found', self::$methods['get']->invoke(null, $array, '0.users.3', fn (): string => 'Not found'));
        $this->assertEquals('Taylor', self::$methods['get']->invoke(null, $dottedArray, ['users', 'first.name']));
        $this->assertNull(self::$methods['get']->invoke(null, $dottedArray, ['users', 'middle.name']));
        $this->assertEquals('Not found', self::$methods['get']->invoke(null, $dottedArray, ['users', 'last.name'], 'Not found'));
    }

    #[Test]
    public function it_returns_target_when_missing_key(): void
    {
        $this->assertEquals(['test'], self::$methods['get']->invoke(null, ['test'], null));
    }

    #[Test]
    public function it_gets_data_with_nested_arrays(): void
    {
        $array = [
            ['name' => 'taylor', 'email' => 'taylorotwell@gmail.com'],
            ['name' => 'abigail'],
            ['name' => 'dayle'],
        ];
        $this->assertEquals(['taylor', 'abigail', 'dayle'], self::$methods['get']->invoke(null, $array, '*.name'));
        $this->assertEquals(['taylorotwell@gmail.com', null, null], self::$methods['get']->invoke(null, $array, '*.email', 'irrelevant'));
        $array = [
            'users' => [
                ['first' => 'taylor', 'last' => 'otwell', 'email' => 'taylorotwell@gmail.com'],
                ['first' => 'abigail', 'last' => 'otwell'],
                ['first' => 'dayle', 'last' => 'rees'],
            ],
            'posts' => null,
        ];
        $this->assertEquals(['taylor', 'abigail', 'dayle'], self::$methods['get']->invoke(null, $array, 'users.*.first'));
        $this->assertEquals(['taylorotwell@gmail.com', null, null], self::$methods['get']->invoke(null, $array, 'users.*.email', 'irrelevant'));
        $this->assertEquals('not found', self::$methods['get']->invoke(null, $array, 'posts.*.date', 'not found'));
        $this->assertNull(self::$methods['get']->invoke(null, $array, 'posts.*.date'));
    }

    #[Test]
    public function it_gets_data_with_nested_double_nested_arrays_and_collapses_result(): void
    {
        $array = [
            'posts' => [
                [
                    'comments' => [
                        ['author' => 'taylor', 'likes' => 4],
                        ['author' => 'abigail', 'likes' => 3],
                    ],
                ],
                [
                    'comments' => [
                        ['author' => 'abigail', 'likes' => 2],
                        ['author' => 'dayle'],
                    ],
                ],
                [
                    'comments' => [
                        ['author' => 'dayle'],
                        ['author' => 'taylor', 'likes' => 1],
                    ],
                ],
            ],
        ];
        $this->assertEquals(['taylor', 'abigail', 'abigail', 'dayle', 'dayle', 'taylor'], self::$methods['get']->invoke(null, $array, 'posts.*.comments.*.author'));
        $this->assertEquals([4, 3, 2, null, null, 1], self::$methods['get']->invoke(null, $array, 'posts.*.comments.*.likes'));
        $this->assertEquals([], self::$methods['get']->invoke(null, $array, 'posts.*.users.*.name', 'irrelevant'));
        $this->assertEquals([], self::$methods['get']->invoke(null, $array, 'posts.*.users.*.name'));
    }

    #[Test]
    public function it_plucks_array(): void
    {
        $data = [
            'post-1' => [
                'comments' => [
                    'tags' => [
                        '#foo', '#bar',
                    ],
                ],
            ],
            'post-2' => [
                'comments' => [
                    'tags' => [
                        '#baz',
                    ],
                ],
            ],
        ];
        $this->assertEquals([
            0 => [
                'tags' => [
                    '#foo', '#bar',
                ],
            ],
            1 => [
                'tags' => [
                    '#baz',
                ],
            ],
        ], self::$methods['pluck']->invoke(null, $data, 'comments'));
        $this->assertEquals([['#foo', '#bar'], ['#baz']], self::$methods['pluck']->invoke(null, $data, 'comments.tags'));
        $this->assertEquals([null, null], self::$methods['pluck']->invoke(null, $data, 'foo'));
        $this->assertEquals([null, null], self::$methods['pluck']->invoke(null, $data, 'foo.bar'));
    }

    #[Test]
    public function it_plucks_array_with_array_and_object_values(): void
    {
        $array = [(object) ['name' => 'taylor', 'email' => 'foo'], ['name' => 'dayle', 'email' => 'bar']];
        $this->assertEquals(['taylor', 'dayle'], self::$methods['pluck']->invoke(null, $array, 'name'));
        $this->assertEquals(['taylor' => 'foo', 'dayle' => 'bar'], self::$methods['pluck']->invoke(null, $array, 'email', 'name'));
    }

    #[Test]
    public function it_plucks_array_with_nested_keys(): void
    {
        $array = [['user' => ['taylor', 'otwell']], ['user' => ['dayle', 'rees']]];
        $this->assertEquals(['taylor', 'dayle'], self::$methods['pluck']->invoke(null, $array, 'user.0'));
        $this->assertEquals(['taylor', 'dayle'], self::$methods['pluck']->invoke(null, $array, ['user', 0]));
        $this->assertEquals(['taylor' => 'otwell', 'dayle' => 'rees'], self::$methods['pluck']->invoke(null, $array, 'user.1', 'user.0'));
        $this->assertEquals(['taylor' => 'otwell', 'dayle' => 'rees'], self::$methods['pluck']->invoke(null, $array, ['user', 1], ['user', 0]));
    }

    #[Test]
    public function it_plucks_array_with_nested_arrays(): void
    {
        $array = [
            [
                'account' => 'a',
                'users' => [
                    ['first' => 'taylor', 'last' => 'otwell', 'email' => 'foo'],
                ],
            ],
            [
                'account' => 'b',
                'users' => [
                    ['first' => 'abigail', 'last' => 'otwell'],
                    ['first' => 'dayle', 'last' => 'rees'],
                ],
            ],
        ];
        $this->assertEquals([['taylor'], ['abigail', 'dayle']], self::$methods['pluck']->invoke(null, $array, 'users.*.first'));
        $this->assertEquals(['a' => ['taylor'], 'b' => ['abigail', 'dayle']], self::$methods['pluck']->invoke(null, $array, 'users.*.first', 'account'));
        $this->assertEquals([['foo'], [null, null]], self::$methods['pluck']->invoke(null, $array, 'users.*.email'));
    }

    #[Test]
    public function it_collapses_array(): void
    {
        $array = [[1], [2], [3], ['foo', 'bar'], ['baz', 'boom']];
        $this->assertEquals([1, 2, 3, 'foo', 'bar', 'baz', 'boom'], self::$methods['collapse']->invoke(null, $array));
    }

    #[Test]
    public function it_gets_file_content(): void
    {
        $this->assertStringEqualsFile(__DIR__.'/../resources/languages.json', self::$methods['getFile']->invoke(null, __DIR__.'/../resources/languages.json'));
    }

    #[Test]
    public function it_throws_an_exception_when_invalid_file(): void
    {
        $this->expectException(LanguageLoaderException::class);

        self::$methods['getFile']->invoke(null, __DIR__.'/../resources/invalid.json');
    }
}
