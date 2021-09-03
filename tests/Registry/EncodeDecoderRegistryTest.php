<?php /** @noinspection ALL */
declare(strict_types=1);
/*
 * This file is part of the jojo1981/decoder-aggregate package
 *
 * Copyright (c) 2021 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\DecoderAggregate\TestSuite\Registry;

use Jojo1981\DecoderAggregate\DecoderInterface;
use Jojo1981\DecoderAggregate\EncoderInterface;
use Jojo1981\DecoderAggregate\Exception\EncodeDecoderRegistryException;
use Jojo1981\DecoderAggregate\Registry\EncodeDecoderRegistry;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Prophecy\Exception\Doubler\DoubleException;
use Prophecy\Exception\Doubler\InterfaceNotFoundException;
use Prophecy\Exception\Prophecy\ObjectProphecyException;
use Prophecy\PhpUnit\ProphecyTrait;
use SebastianBergmann\RecursionContext\InvalidArgumentException as SebastianBergmannRecursionContextInvalidArgumentException;

/**
 * @package Jojo1981\DecoderAggregate\TestSuite\Registry
 */
final class EncodeDecoderRegistryTest extends TestCase
{
    use ProphecyTrait;

    /** @var EncodeDecoderRegistry|null */
    private ?EncodeDecoderRegistry $encodeDecoderRegistry = null;

    /**
     * @return void
     * @throws DoubleException
     * @throws InterfaceNotFoundException
     * @throws ObjectProphecyException
     * @throws EncodeDecoderRegistryException
     */
    public function testRegisterEncoderWithAlreadyExistingEncoderShouldThrowEncodeDecoderRegistryException(): void
    {
        $this->expectExceptionObject(EncodeDecoderRegistryException::encoderForFormatAlreadyExists('my-format'));
        $this->getEncodeDecoderRegistry()->registerEncoder('my-format', $this->prophesize(EncoderInterface::class)->reveal());
        $this->getEncodeDecoderRegistry()->registerEncoder('my-format', $this->prophesize(EncoderInterface::class)->reveal());
    }

    /**
     * @return void
     * @throws EncodeDecoderRegistryException
     * @throws InterfaceNotFoundException
     * @throws ObjectProphecyException
     * @throws DoubleException
     */
    public function testRegisterDecoderWithAlreadyExistingDecoderShouldThrowEncodeDecoderRegistryException(): void
    {
        $this->expectExceptionObject(EncodeDecoderRegistryException::decoderForFormatAlreadyExists('my-format'));
        $this->getEncodeDecoderRegistry()->registerDecoder('my-format', $this->prophesize(DecoderInterface::class)->reveal());
        $this->getEncodeDecoderRegistry()->registerDecoder('my-format', $this->prophesize(DecoderInterface::class)->reveal());
    }

    /**
     * @return void
     * @throws EncodeDecoderRegistryException
     */
    public function testGetEncoderWithUnregisteredFormatShouldThrowEncodeDecoderRegistryException(): void
    {
        $this->expectExceptionObject(EncodeDecoderRegistryException::encoderForFormatDoesNotExists('not-registered-format'));
        $this->getEncodeDecoderRegistry()->getEncoder('not-registered-format');
    }

    /**
     * @return void
     * @throws EncodeDecoderRegistryException
     */
    public function testGetDecoderWithUnregisteredFormatShouldThrowEncodeDecoderRegistryException(): void
    {
        $this->expectExceptionObject(EncodeDecoderRegistryException::decoderForFormatDoesNotExists('not-registered-format'));
        $this->getEncodeDecoderRegistry()->getDecoder('not-registered-format');
    }

    public function testHasEncoderShouldReturnTrueWhenEncoderForFormatIsRegistered(): void
    {
        $this->getEncodeDecoderRegistry()->registerEncoder('registered-format', $this->prophesize(EncoderInterface::class)->reveal());
        self::assertTrue($this->getEncodeDecoderRegistry()->hasEncoder('registered-format'));

    }

    /**
     * @return void
     * @throws SebastianBergmannRecursionContextInvalidArgumentException
     * @throws ExpectationFailedException
     */
    public function testHasEncoderShouldReturnFalseWhenEncoderIsNotForFormatRegistered(): void
    {
        self::assertFalse($this->getEncodeDecoderRegistry()->hasEncoder('not-registered-format'));
    }

    /**
     * @return void
     * @throws InterfaceNotFoundException
     * @throws ObjectProphecyException
     * @throws ExpectationFailedException
     * @throws SebastianBergmannRecursionContextInvalidArgumentException
     * @throws DoubleException
     */
    public function testHasDecoderShouldReturnTrueWhenDecoderForFormatIsRegistered(): void
    {
        $this->getEncodeDecoderRegistry()->registerDecoder('registered-format', $this->prophesize(DecoderInterface::class)->reveal());
        self::assertTrue($this->getEncodeDecoderRegistry()->hasDecoder('registered-format'));
    }

    /**
     * @return void
     * @throws SebastianBergmannRecursionContextInvalidArgumentException
     * @throws ExpectationFailedException
     */
    public function testHasDecoderShouldReturnFalseWhenDecoderIsNotForFormatRegistered(): void
    {
        self::assertFalse($this->getEncodeDecoderRegistry()->hasDecoder('not-registered-format'));
    }

    /**
     * @return void
     * @throws EncodeDecoderRegistryException
     * @throws InterfaceNotFoundException
     * @throws ObjectProphecyException
     * @throws ExpectationFailedException
     * @throws SebastianBergmannRecursionContextInvalidArgumentException
     * @throws DoubleException
     */
    public function testGetEncoderShouldReturnTheRightEncoderForFormat(): void
    {
        /** @var EncoderInterface $encoder */
        $encoder = $this->prophesize(EncoderInterface::class)->reveal();
        $this->getEncodeDecoderRegistry()->registerEncoder('registered-format', $encoder);
        self::assertSame($encoder, $this->getEncodeDecoderRegistry()->getEncoder('registered-format'));
    }

    public function testGetDecoderShouldReturnTheRightDecoderForFormat(): void
    {
        /** @var DecoderInterface $decoder */
        $decoder = $this->prophesize(DecoderInterface::class)->reveal();
        $this->getEncodeDecoderRegistry()->registerDecoder('registered-format', $decoder);
        self::assertSame($decoder, $this->getEncodeDecoderRegistry()->getDecoder('registered-format'));
    }

    /**
     * @return EncodeDecoderRegistry
     */
    private function getEncodeDecoderRegistry(): EncodeDecoderRegistry
    {
        if (null === $this->encodeDecoderRegistry) {
            $this->encodeDecoderRegistry = new EncodeDecoderRegistry();
        }

        return $this->encodeDecoderRegistry;
    }
}
