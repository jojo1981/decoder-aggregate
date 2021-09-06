<?php declare(strict_types=1);
/*
 * This file is part of the jojo1981/decoder-aggregate package
 *
 * Copyright (c) 2021 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\DecoderAggregate\TestSuite\Encoder;

use Jojo1981\DecoderAggregate\Encoder\YamlEncoder;
use Jojo1981\DecoderAggregate\Exception\YamlEncoderException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use stdClass;
use Symfony\Component\Yaml\Exception\DumpException;
use Symfony\Component\Yaml\Yaml;
use Throwable;
use function fopen;

/**
 * @package Jojo1981\DecoderAggregate\TestSuite\Encoder
 */
final class YamlEncoderTest extends TestCase
{
    /**
     * @return void
     * @throws InvalidArgumentException
     * @throws YamlEncoderException
     * @throws ExpectationFailedException
     */
    public function testEncodeWithInvalidDataShouldThrowYamlEncoderException(): void
    {
        $previousException = new DumpException('Unable to dump PHP resources in a YAML file ("stream").');
        $this->expectExceptionObject(new YamlEncoderException('Could not encode data into a yaml string', 0, $previousException));
        try {
            (new YamlEncoder(2, 4, Yaml::DUMP_EXCEPTION_ON_INVALID_TYPE))->encode(fopen(__FILE__, 'rb'));
        } catch (Throwable $thrownException) {
            self::assertEquals($previousException, $thrownException->getPrevious());
            throw $thrownException;
        }
    }

    /**
     * @dataProvider getTestData
     *
     * @param int $flags
     * @param mixed $data
     * @param array $options
     * @param mixed $expectedResult
     * @return void
     * @throws InvalidArgumentException
     * @throws YamlEncoderException
     * @throws ExpectationFailedException
     */
    public function testEncode(int $flags, $data, array $options, $expectedResult): void
    {
        self::assertEquals($expectedResult, (new YamlEncoder(2, 4, $flags))->encode($data, $options));
    }

    /**
     * @return array[]
     */
    public function getTestData(): array
    {
        return [
            [0, $this->getTestData0(), [], $this->getExpectedResult0()],
            [Yaml::DUMP_OBJECT_AS_MAP, $this->getTestData0(), [], $this->getExpectedResult0()],
            [0, $this->getTestData0(), ['flags' => Yaml::DUMP_OBJECT_AS_MAP], $this->getExpectedResult0()],
            [Yaml::DUMP_OBJECT_AS_MAP, $this->getTestData0(), ['flags' => Yaml::DUMP_OBJECT_AS_MAP], $this->getExpectedResult0()],
            [Yaml::DUMP_OBJECT, $this->getTestData1(), [], $this->getExpectedResult1()],
            [0, $this->getTestData1(), ['flags' => Yaml::DUMP_OBJECT], $this->getExpectedResult1()],
            [Yaml::DUMP_OBJECT, $this->getTestData1(), ['flags' => Yaml::DUMP_OBJECT], $this->getExpectedResult1()],
            [Yaml::DUMP_NULL_AS_TILDE, $this->getTestData0(), [], $this->getExpectedResult3()],
            [0, $this->getTestData0(), ['flags' => Yaml::DUMP_NULL_AS_TILDE], $this->getExpectedResult3()],
            [Yaml::DUMP_NULL_AS_TILDE, $this->getTestData0(), ['flags' => Yaml::DUMP_NULL_AS_TILDE], $this->getExpectedResult3()],
            [Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE, $this->getTestData0(), [], $this->getExpectedResult4()],
            [0, $this->getTestData0(), ['flags' => Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE], $this->getExpectedResult4()],
            [Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE, $this->getTestData0(), ['flags' => Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE], $this->getExpectedResult4()],
        ];
    }

    /**
     * @return array
     */
    private function getTestData0(): array
    {
        return [
            'name' => 'John Doe',
            'list' => ['text', null, true, false, [], [], ['a' => 'b']],
            'object' => [
                'propertyName' => 'value'
            ],
            'val' => null
        ];
    }

    /**
     * @return stdClass
     */
    private function getTestData1(): stdClass
    {
        return (object) [
            'name' => 'John Doe',
            'list' => ['text', null, true, false, [], [], (object) ['a' => 'b']],
            'object' => (object) [
                'propertyName' => 'value'
            ]
        ];
    }

    /**
     * @return string
     */
    private function getExpectedResult0(): string
    {
        return <<<YAML
name: 'John Doe'
list:
    - text
    - null
    - true
    - false
    - {  }
    - {  }
    - { a: b }
object:
    propertyName: value
val: null

YAML;
    }

    /**
     * @return string
     */
    private function getExpectedResult1(): string
    {
        return '!php/object \'O:8:"stdClass":3:{s:4:"name";s:8:"John Doe";s:4:"list";a:7:{i:0;s:4:"text";i:1;N;i:2;b:1;i:3;b:0;i:4;a:0:{}i:5;a:0:{}i:6;O:8:"stdClass":1:{s:1:"a";s:1:"b";}}s:6:"object";O:8:"stdClass":1:{s:12:"propertyName";s:5:"value";}}\'';
    }

    /**
     * @return string
     */
    private function getExpectedResult3(): string
    {
        return <<<YAML
name: 'John Doe'
list:
    - text
    - ~
    - true
    - false
    - {  }
    - {  }
    - { a: b }
object:
    propertyName: value
val: ~

YAML;
    }

    /**
     * @return string
     */
    private function getExpectedResult4(): string
    {
        return <<<YAML
name: 'John Doe'
list:
    - text
    - null
    - true
    - false
    - []
    - []
    - { a: b }
object:
    propertyName: value
val: null

YAML;
    }
}
