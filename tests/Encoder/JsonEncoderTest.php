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

use Jojo1981\DecoderAggregate\Encoder\JsonEncoder;
use Jojo1981\DecoderAggregate\Exception\JsonEncoderException;
use JsonException;
use PHPUnit\Framework\Exception as PhpUnitFrameworkException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use stdClass;
use Throwable;

/**
 * @package Jojo1981\DecoderAggregate\TestSuite\Encoder
 */
final class JsonEncoderTest extends TestCase
{
    /**
     * @return void
     * @throws InvalidArgumentException
     * @throws JsonEncoderException
     * @throws PhpUnitFrameworkException
     * @throws ExpectationFailedException
     */
    public function testEncodeWithInvalidDataShouldThrowJsonEncoderException(): void
    {
        $previousException = new JsonException('Malformed UTF-8 characters, possibly incorrectly encoded', 5);
        $this->expectExceptionObject(new JsonEncoderException('Could not encode data into a json string', 0, $previousException));
        try {
            (new JsonEncoder(JSON_THROW_ON_ERROR))->encode("\xB1\x31");
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
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws JsonEncoderException
     */
    public function testEncode(int $flags, $data, array $options, $expectedResult): void
    {
        self::assertEquals($expectedResult, (new JsonEncoder($flags))->encode($data, $options));
    }

    /**
     * @return array[]
     */
    public function getTestData(): array
    {
        return [
            [0, $this->getData0(), [], $this->getExpectedResult0()],
            [JSON_PRETTY_PRINT, $this->getData0(), [], $this->getExpectedResult1()],
            [0, $this->getData0(), ['flags' => JSON_PRETTY_PRINT], $this->getExpectedResult1()],
            [JSON_PRETTY_PRINT, $this->getData0(), ['flags' => JSON_PRETTY_PRINT], $this->getExpectedResult1()]
        ];
    }

    /**
     * @return stdClass
     */
    private function getData0(): stdClass
    {
        $item = new stdClass();
        $item->a = 'b';

        $result = new stdClass();
        $result->name = 'John Doe';
        $result->float = 2.3;
        $result->int = 7;
        $result->list = ['text', null, true, false, [], new stdClass(), $item];
        $result->object = new stdClass();
        $result->object->propertyName = 'value';

        return $result;
    }

    /**
     * @return string
     */
    private function getExpectedResult0(): string
    {
        return '{"name":"John Doe","float":2.3,"int":7,"list":["text",null,true,false,[],{},{"a":"b"}],"object":{"propertyName":"value"}}';
    }

    /**
     * @return string
     */
    private function getExpectedResult1(): string
    {
        return <<<JSON
{
    "name": "John Doe",
    "float": 2.3,
    "int": 7,
    "list": [
        "text",
        null,
        true,
        false,
        [],
        {},
        {
            "a": "b"
        }
    ],
    "object": {
        "propertyName": "value"
    }
}
JSON;
    }
}
