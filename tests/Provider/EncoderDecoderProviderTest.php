<?php /** @noinspection PhpUndefinedMethodInspection */
declare(strict_types=1);
/*
 * This file is part of the jojo1981/decoder-aggregate package
 *
 * Copyright (c) 2021 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\DecoderAggregate\TestSuite\Provider;

use Jojo1981\DecoderAggregate\DecoderInterface;
use Jojo1981\DecoderAggregate\EncodeDecoderRegistryInterface;
use Jojo1981\DecoderAggregate\EncoderInterface;
use Jojo1981\DecoderAggregate\Exception\EncodeDecoderRegistryException;
use Jojo1981\DecoderAggregate\Exception\EncoderDecoderProviderException;
use Jojo1981\DecoderAggregate\Provider\EncoderDecoderProvider;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Prophecy\Exception\Doubler\DoubleException;
use Prophecy\Exception\Doubler\InterfaceNotFoundException;
use Prophecy\Exception\Prophecy\ObjectProphecyException;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Throwable;

/**
 * @package Jojo1981\DecoderAggregate\TestSuite\Provider
 */
final class EncoderDecoderProviderTest extends TestCase
{
    use ProphecyTrait;

    /** @var ObjectProphecy|EncodeDecoderRegistryInterface */
    private ObjectProphecy $encodeDecoderRegistry;

    /**
     * @return void
     * @throws InterfaceNotFoundException
     * @throws DoubleException
     */
    protected function setUp(): void
    {
        $this->encodeDecoderRegistry = $this->prophesize(EncodeDecoderRegistryInterface::class);
    }

    /**
     * @return void
     * @throws EncoderDecoderProviderException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ObjectProphecyException
     * @throws EncodeDecoderRegistryException
     */
    public function testGetEncoderNotRegisteredEncoderShouldThrowEncoderDecoderProviderException(): void
    {
        $encodeDecoderRegistryException = EncodeDecoderRegistryException::encoderForFormatDoesNotExists('not-registered-format');
        $this->expectExceptionObject(EncoderDecoderProviderException::couldNotGetEncoderForFormat('not-registered-format', $encodeDecoderRegistryException));
        $this->encodeDecoderRegistry->getEncoder('not-registered-format')->willThrow($encodeDecoderRegistryException)->shouldBeCalledOnce();
        try {
            $this->getEncoderDecoderProvider()->getEncoder('not-registered-format');
        } catch (Throwable $thrownException) {
            self::assertSame($encodeDecoderRegistryException, $thrownException->getPrevious());
            throw $thrownException;
        }
    }

    /**
     * @return void
     * @throws EncoderDecoderProviderException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ObjectProphecyException
     * @throws EncodeDecoderRegistryException
     */
    public function testGetDecoderNotRegisteredEncoderShouldThrowEncoderDecoderProviderException(): void
    {
        $encodeDecoderRegistryException = EncodeDecoderRegistryException::decoderForFormatDoesNotExists('not-registered-format');
        $this->expectExceptionObject(EncoderDecoderProviderException::couldNotGetDecoderForFormat('not-registered-format', $encodeDecoderRegistryException));
        $this->encodeDecoderRegistry->getDecoder('not-registered-format')->willThrow($encodeDecoderRegistryException)->shouldBeCalledOnce();
        try {
            $this->getEncoderDecoderProvider()->getDecoder('not-registered-format');
        } catch (Throwable $thrownException) {
            self::assertSame($encodeDecoderRegistryException, $thrownException->getPrevious());
            throw $thrownException;
        }
    }

    /**
     * @return void
     * @throws EncodeDecoderRegistryException
     * @throws EncoderDecoderProviderException
     * @throws ExpectationFailedException
     * @throws InterfaceNotFoundException
     * @throws InvalidArgumentException
     * @throws ObjectProphecyException
     * @throws DoubleException
     */
    public function testGetEncoderShouldReturnTheRightRegisteredEncoder(): void
    {
        /** @var EncoderInterface $encoder */
        $encoder = $this->prophesize(EncoderInterface::class)->reveal();
        $this->encodeDecoderRegistry->getEncoder('my-registered-format')->willReturn($encoder)->shouldBeCalledOnce();
        self::assertSame($encoder, $this->getEncoderDecoderProvider()->getEncoder('my-registered-format'));
    }

    /**
     * @return void
     * @throws EncodeDecoderRegistryException
     * @throws EncoderDecoderProviderException
     * @throws ExpectationFailedException
     * @throws InterfaceNotFoundException
     * @throws InvalidArgumentException
     * @throws ObjectProphecyException
     * @throws DoubleException
     */
    public function testGetDecoderShouldReturnTheRightRegisteredEncoder(): void
    {
        /** @var DecoderInterface $decoder */
        $decoder = $this->prophesize(DecoderInterface::class)->reveal();
        $this->encodeDecoderRegistry->getDecoder('my-registered-format')->willReturn($decoder)->shouldBeCalledOnce();
        self::assertSame($decoder, $this->getEncoderDecoderProvider()->getDecoder('my-registered-format'));
    }

    /**
     * @return EncoderDecoderProvider
     * @throws ObjectProphecyException
     */
    private function getEncoderDecoderProvider(): EncoderDecoderProvider
    {
        return new EncoderDecoderProvider($this->encodeDecoderRegistry->reveal());
    }
}
