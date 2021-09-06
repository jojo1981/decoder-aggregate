<?php declare(strict_types=1);
/*
 * This file is part of the jojo1981/decoder-aggregate package
 *
 * Copyright (c) 2021 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\DecoderAggregate\TestSuite\Decoder;

use DateTime;
use Exception;
use Jojo1981\DecoderAggregate\Decoder\YamlDecoder;
use Jojo1981\DecoderAggregate\Exception\YamlDecodeException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use stdClass;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Tag\TaggedValue;
use Symfony\Component\Yaml\Yaml;
use Throwable;
use function define;
use function defined;

/**
 * @package Jojo1981\DecoderAggregate\TestSuite\Decoder
 */
final class YamlDecoderTest extends TestCase
{
    /**
     * @return void
     * @throws InvalidArgumentException
     * @throws YamlDecodeException
     * @throws ExpectationFailedException
     */
    public function testDecodeShouldThrowJsonDecodeExceptionBecauseInvalidEncodedStringGiven(): void
    {
        $parseException = new ParseException('Reference "*[]a" does not exist.', 1, '**[]a:');
        $this->expectExceptionObject(new YamlDecodeException('Could not decode yaml string', 0, $parseException));
        try {
            (new YamlDecoder())->decode('- **[]a:');
        } catch (Throwable $thrownException) {
            self::assertEquals($parseException, $thrownException->getPrevious());
            throw $thrownException;
        }
    }

    /**
     * @dataProvider getTestData
     *
     * @param int $flags
     * @param string $encodedString
     * @param array $options
     * @param mixed $expectedResult
     * @return void
     * @throws InvalidArgumentException
     * @throws YamlDecodeException
     * @throws ExpectationFailedException
     */
    public function testDecode(int $flags, string $encodedString, array $options, $expectedResult): void
    {
        self::assertEquals($expectedResult, (new YamlDecoder($flags))->decode($encodedString, $options));
    }

    /**
     * @return array[]
     * @throws Exception
     */
    public function getTestData(): array
    {
        return [
            [0, $this->getEncodedString0(), [], $this->getExpectedResult0()],
            [Yaml::PARSE_OBJECT_FOR_MAP, $this->getEncodedString0(), [], $this->getExpectedResult1()],
            [0, $this->getEncodedString0(), ['flags' => Yaml::PARSE_OBJECT_FOR_MAP], $this->getExpectedResult1()],
            [Yaml::PARSE_OBJECT_FOR_MAP, $this->getEncodedString0(), ['flags' => Yaml::PARSE_OBJECT_FOR_MAP], $this->getExpectedResult1()],
            [Yaml::PARSE_CUSTOM_TAGS, $this->getEncodedString1(), [], $this->getExpectedResult2()],
            [0, $this->getEncodedString1(), ['flags' => Yaml::PARSE_CUSTOM_TAGS], $this->getExpectedResult2()],
            [Yaml::PARSE_CUSTOM_TAGS, $this->getEncodedString1(), ['flags' => Yaml::PARSE_CUSTOM_TAGS], $this->getExpectedResult2()],
            [Yaml::PARSE_OBJECT, $this->getEncodedString2(), [], $this->getExpectedResult3()],
            [0, $this->getEncodedString2(), ['flags' => Yaml::PARSE_OBJECT], $this->getExpectedResult3()],
            [Yaml::PARSE_OBJECT, $this->getEncodedString2(), ['flags' => Yaml::PARSE_OBJECT], $this->getExpectedResult3()],
            [Yaml::PARSE_DATETIME, $this->getEncodedString3(), [], $this->getExpectedResult4()],
            [0, $this->getEncodedString3(), ['flags' => Yaml::PARSE_DATETIME], $this->getExpectedResult4()],
            [Yaml::PARSE_DATETIME, $this->getEncodedString3(), ['flags' => Yaml::PARSE_DATETIME], $this->getExpectedResult4()],
            [Yaml::PARSE_CONSTANT, $this->getEncodedString4(), [], $this->getExpectedResult5()],
            [0, $this->getEncodedString4(), ['flags' => Yaml::PARSE_CONSTANT], $this->getExpectedResult5()],
            [Yaml::PARSE_CONSTANT, $this->getEncodedString4(), ['flags' => Yaml::PARSE_CONSTANT], $this->getExpectedResult5()]
        ];
    }

    /**
     * @return string
     */
    private function getEncodedString0(): string
    {
        return <<<YAML
name: "John Doe"
list:
   - "text"
   - null
   - true
   - false
   - []
   - {}
   - 
      a: "b"
object:
  propertyName: 'value'
YAML;
    }

    /**
     * @return string
     */
    private function getEncodedString1(): string
    {
        return <<<YAML
name: "John Doe"
tag: !my_tag { foo: bar }
YAML;
    }

    /**
     * @return string
     */
    private function getEncodedString2(): string
    {
        return '!php/object \'O:8:"stdClass":1:{s:3:"foo";s:3:"bar";}\'';
    }

    /**
     * @return string
     */
    private function getEncodedString3(): string
    {
        return <<<YAML
name: "John Doe"
date: 2016-05-27
YAML;
    }

    /**
     * @return string
     */
    private function getEncodedString4(): string
    {
        if (!defined('TEST_CONSTANT')) {
            define('TEST_CONSTANT', 214);
        }

        return '{ foo: TEST_CONSTANT, bar: !php/const TEST_CONSTANT }';
    }

    /**
     * @return array
     */
    private function getExpectedResult0(): array
    {
        return [
            'name' => 'John Doe',
            'list' => ['text', null, true, false, [], [], ['a' => 'b']],
            'object' => [
                'propertyName' => 'value'
            ]
        ];
    }

    /**
     * @return stdClass
     */
    private function getExpectedResult1(): stdClass
    {
        return (object) [
            'name' => 'John Doe',
            'list' => ['text', null, true, false, [], new stdClass(), (object) ['a' => 'b']],
            'object' => (object) [
                'propertyName' => 'value'
            ]
        ];
    }

    /**
     * @return array
     */
    private function getExpectedResult2(): array
    {
        return [
            'name' => 'John Doe',
            'tag' => new TaggedValue('my_tag', ['foo' => 'bar'])
        ];
    }

    /**
     * @return stdClass
     */
    private function getExpectedResult3(): stdClass
    {
        return (object) ['foo' => 'bar'];
    }

    /**
     * @return array
     * @throws Exception
     */
    private function getExpectedResult4(): array
    {
        return [
            'name' => 'John Doe',
            'date' => new DateTime('2016-05-27')
        ];
    }

    /**
     * @return array
     */
    private function getExpectedResult5(): array
    {
        return [
            'foo' => 'TEST_CONSTANT',
            'bar' => 214
        ];
    }
}
