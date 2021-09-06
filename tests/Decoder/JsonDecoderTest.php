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

use Jojo1981\DecoderAggregate\Decoder\JsonDecoder;
use Jojo1981\DecoderAggregate\Exception\JsonDecodeException;
use JsonException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use stdClass;
use Throwable;

/**
 * @package Jojo1981\DecoderAggregate\TestSuite\Decoder
 */
final class JsonDecoderTest extends TestCase
{
    /**
     * @return void
     * @throws InvalidArgumentException
     * @throws JsonDecodeException
     * @throws ExpectationFailedException
     */
    public function testDecodeShouldThrowJsonDecodeExceptionBecauseInvalidEncodedStringGiven(): void
    {
        $jsonException = new JsonException('Syntax error', 4);
        $this->expectExceptionObject(new JsonDecodeException('Could not decode json string', 0, $jsonException));
        try {
            (new JsonDecoder(false, 512, JSON_THROW_ON_ERROR))->decode('a');
        } catch (Throwable $thrownException) {
            self::assertEquals($jsonException, $thrownException->getPrevious());
            throw $thrownException;
        }
    }

    /**
     * @dataProvider getTestData
     *
     * @param bool|null $associative
     * @param string $encodedString
     * @param array $options
     * @param mixed $expectedResult
     * @return void
     * @throws InvalidArgumentException
     * @throws JsonDecodeException
     * @throws ExpectationFailedException
     */
    public function testDecode(?bool $associative, string $encodedString, array $options, $expectedResult): void
    {
        $jsonDecoder = null !== $associative ? new JsonDecoder($associative) : new JsonDecoder();
        self::assertEquals($expectedResult, $jsonDecoder->decode($encodedString, $options));
    }

    /**
     * @return array[]
     */
    public function getTestData(): array
    {
        return [
            [null, $this->getEncodedString(), [], $this->getExpectedResult0()],
            [false, $this->getEncodedString(), [], $this->getExpectedResult0()],
            [true, $this->getEncodedString(), [], $this->getExpectedResult1()],
            [null, $this->getEncodedString(), ['associative' => true], $this->getExpectedResult1()],
            [null, $this->getEncodedString(), ['associative' => false], $this->getExpectedResult0()]
        ];
    }

    /**
     * @return string
     */
    private function getEncodedString(): string
    {
        return <<<JSON
{
   "name": "John Doe",
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

    /**
     * @return stdClass
     */
    private function getExpectedResult0(): stdClass
    {
        $item = new stdClass();
        $item->a = 'b';

        $result = new stdClass();
        $result->name = 'John Doe';
        $result->list = ['text', null, true, false, [], new stdClass(), $item];
        $result->object = new stdClass();
        $result->object->propertyName = 'value';

        return $result;
    }

    /**
     * @return array
     */
    private function getExpectedResult1(): array
    {
        return [
            'name' => 'John Doe',
            'list' => ['text', null, true, false, [], [], ['a' => 'b']],
            'object' => [
                'propertyName' => 'value'
            ]
        ];
    }
}
