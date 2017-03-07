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

use ReflectionClass;
use Rinvex\Language\Language;
use PHPUnit_Framework_TestCase;
use Rinvex\Language\LanguageLoader;
use Rinvex\Language\LanguageLoaderException;

class LanguageLoaderTest extends PHPUnit_Framework_TestCase
{
    /** @var array */
    protected static $methods;

    public static function setUpBeforeClass()
    {
        $reflectedLoader = new ReflectionClass(LanguageLoader::class);
        self::$methods['get'] = $reflectedLoader->getMethod('get');
        self::$methods['pluck'] = $reflectedLoader->getMethod('pluck');
        self::$methods['filter'] = $reflectedLoader->getMethod('filter');
        self::$methods['getFile'] = $reflectedLoader->getMethod('getFile');
        self::$methods['collapse'] = $reflectedLoader->getMethod('collapse');

        foreach (self::$methods as $method) {
            $method->setAccessible(true);
        }
    }

    public static function tearDownAfterClass()
    {
        self::$methods = null;
    }

    /** @test */
    public function it_returns_language_data()
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

    /** @test */
    public function it_gets_data_with_where_conditions()
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

    /** @test */
    public function it_returns_languages_array()
    {
        $this->assertEquals(183, count(LanguageLoader::languages()));
        $this->assertInternalType('array', LanguageLoader::languages()['am']);
        $this->assertEquals('English', LanguageLoader::languages()['en']['name']);
    }

    /** @test */
    public function it_returns_language_scripts_array()
    {
        $this->assertEquals(29, count(LanguageLoader::scripts()));
        $this->assertInternalType('array', LanguageLoader::scripts());
        $this->assertArrayHasKey('Arab', LanguageLoader::scripts());
    }

    /** @test */
    public function it_returns_language_families_array()
    {
        $this->assertEquals(27, count(LanguageLoader::families()));
        $this->assertInternalType('array', LanguageLoader::families());
        $this->assertArrayHasKey('afa', LanguageLoader::families());
    }

    /** @test */
    public function it_returns_language_hydrated()
    {
        $this->assertEquals(183, count(LanguageLoader::languages(true)));
        $this->assertInternalType('object', LanguageLoader::languages(true)['en']);
        $this->assertEquals('English', LanguageLoader::languages(true)['en']->getName());
    }

    /** @test */
    public function it_throws_an_exception_when_invalid_language()
    {
        $this->expectException(LanguageLoaderException::class);

        LanguageLoader::language('asd');
    }

    /** @test */
    public function it_filters_data()
    {
        $array1 = [['id' => 1, 'name' => 'Hello'], ['id' => 2, 'name' => 'World']];
        $this->assertEquals([1 => ['id' => 2, 'name' => 'World']], self::$methods['filter']->invoke(null, $array1, function ($item) {
            return $item['id'] === 2;
        }));

        $array2 = ['', 'Hello', '', 'World'];
        $this->assertEquals(['Hello', 'World'], array_values(self::$methods['filter']->invoke(null, $array2)));

        $array3 = ['id' => 1, 'first' => 'Hello', 'second' => 'World'];
        $this->assertEquals(['first' => 'Hello', 'second' => 'World'], self::$methods['filter']->invoke(null, $array3, function ($item, $key) {
            return $key !== 'id';
        }));
    }

    /** @test */
    public function it_gets_data()
    {
        $object = (object) ['users' => ['name' => ['Taylor', 'Otwell']]];
        $array = [(object) ['users' => [(object) ['name' => 'Taylor']]]];
        $dottedArray = ['users' => ['first.name' => 'Taylor', 'middle.name' => null]];
        $this->assertEquals('Taylor', self::$methods['get']->invoke(null, $object, 'users.name.0'));
        $this->assertEquals('Taylor', self::$methods['get']->invoke(null, $array, '0.users.0.name'));
        $this->assertNull(self::$methods['get']->invoke(null, $array, '0.users.3'));
        $this->assertEquals('Not found', self::$methods['get']->invoke(null, $array, '0.users.3', 'Not found'));
        $this->assertEquals('Not found', self::$methods['get']->invoke(null, $array, '0.users.3', function () {
            return 'Not found';
        }));
        $this->assertEquals('Taylor', self::$methods['get']->invoke(null, $dottedArray, ['users', 'first.name']));
        $this->assertNull(self::$methods['get']->invoke(null, $dottedArray, ['users', 'middle.name']));
        $this->assertEquals('Not found', self::$methods['get']->invoke(null, $dottedArray, ['users', 'last.name'], 'Not found'));
    }

    /** @test */
    public function it_returns_target_when_missing_key()
    {
        $this->assertEquals(['test'], self::$methods['get']->invoke(null, ['test'], null));
    }

    /** @test */
    public function it_gets_data_with_nested_arrays()
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

    /** @test */
    public function it_gets_data_with_nested_double_nested_arrays_and_collapses_result()
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

    /** @test */
    public function it_plucks_array()
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

    /** @test */
    public function it_plucks_array_with_array_and_object_values()
    {
        $array = [(object) ['name' => 'taylor', 'email' => 'foo'], ['name' => 'dayle', 'email' => 'bar']];
        $this->assertEquals(['taylor', 'dayle'], self::$methods['pluck']->invoke(null, $array, 'name'));
        $this->assertEquals(['taylor' => 'foo', 'dayle' => 'bar'], self::$methods['pluck']->invoke(null, $array, 'email', 'name'));
    }

    /** @test */
    public function it_plucks_array_with_nested_keys()
    {
        $array = [['user' => ['taylor', 'otwell']], ['user' => ['dayle', 'rees']]];
        $this->assertEquals(['taylor', 'dayle'], self::$methods['pluck']->invoke(null, $array, 'user.0'));
        $this->assertEquals(['taylor', 'dayle'], self::$methods['pluck']->invoke(null, $array, ['user', 0]));
        $this->assertEquals(['taylor' => 'otwell', 'dayle' => 'rees'], self::$methods['pluck']->invoke(null, $array, 'user.1', 'user.0'));
        $this->assertEquals(['taylor' => 'otwell', 'dayle' => 'rees'], self::$methods['pluck']->invoke(null, $array, ['user', 1], ['user', 0]));
    }

    /** @test */
    public function it_plucks_array_with_nested_arrays()
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

    /** @test */
    public function it_collapses_array()
    {
        $array = [[1], [2], [3], ['foo', 'bar'], ['baz', 'boom']];
        $this->assertEquals([1, 2, 3, 'foo', 'bar', 'baz', 'boom'], self::$methods['collapse']->invoke(null, $array));
    }

    /** @test */
    public function it_gets_file_content()
    {
        $this->assertStringEqualsFile(__DIR__.'/../resources/languages.json', self::$methods['getFile']->invoke(null, __DIR__.'/../resources/languages.json'));
    }

    /** @test */
    public function it_throws_an_exception_when_invalid_file()
    {
        $this->expectException(LanguageLoaderException::class);

        self::$methods['getFile']->invoke(null, __DIR__.'/../resources/invalid.json');
    }
}
