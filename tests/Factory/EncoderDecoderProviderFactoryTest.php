<?php /** @noinspection PhpParamsInspection */
declare(strict_types=1);
/*
 * This file is part of the jojo1981/decoder-aggregate package
 *
 * Copyright (c) 2021 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\DecoderAggregate\TestSuite\Factory;

use Jojo1981\DecoderAggregate\Decoder\JsonDecoder;
use Jojo1981\DecoderAggregate\Decoder\YamlDecoder;
use Jojo1981\DecoderAggregate\DecoderInterface;
use Jojo1981\DecoderAggregate\Encoder\JsonEncoder;
use Jojo1981\DecoderAggregate\Encoder\YamlEncoder;
use Jojo1981\DecoderAggregate\EncoderDecoderProviderInterface;
use Jojo1981\DecoderAggregate\EncoderInterface;
use Jojo1981\DecoderAggregate\Exception\EncodeDecoderRegistryException;
use Jojo1981\DecoderAggregate\Exception\EncoderDecoderProviderException;
use Jojo1981\DecoderAggregate\Exception\EncoderDecoderProviderFactoryException;
use Jojo1981\DecoderAggregate\Factory\EncoderDecoderProviderFactory;
use PHPUnit\Framework\Exception as PhpUnitFrameworkException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Prophecy\Exception\Doubler\DoubleException;
use Prophecy\Exception\Doubler\InterfaceNotFoundException;
use Prophecy\Exception\Prophecy\ObjectProphecyException;
use Prophecy\PhpUnit\ProphecyTrait;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * @package Jojo1981\DecoderAggregate\TestSuite\Factory
 */
final class EncoderDecoderProviderFactoryTest extends TestCase
{
    use ProphecyTrait;

    /** @var EncoderDecoderProviderFactory|null */
    private ?EncoderDecoderProviderFactory $encoderDecoderProviderFactory = null;

    /**
     * @return void
     * @throws EncoderDecoderProviderFactoryException
     * @throws EncodeDecoderRegistryException
     */
    public function testAddDefaultEncodersWhenFrozenShouldThrowEncoderDecoderProviderFactoryException()
    {
        $this->expectExceptionObject(EncoderDecoderProviderFactoryException::factoryIsFrozen(
            EncoderDecoderProviderFactory::class,
            EncoderDecoderProviderFactory::class . '::addDefaultEncoders',
            'getEncoderDecoderProvider',
            EncoderDecoderProviderInterface::class
        ));
        $this->getEncoderDecoderProviderFactory()->getEncoderDecoderProvider();
        $this->getEncoderDecoderProviderFactory()->addDefaultEncoders();
    }

    /**
     * @return void
     * @throws EncoderDecoderProviderFactoryException
     * @throws EncodeDecoderRegistryException
     */
    public function testAddDefaultDecodersWhenFrozenShouldThrowEncoderDecoderProviderFactoryException()
    {
        $this->expectExceptionObject(EncoderDecoderProviderFactoryException::factoryIsFrozen(
            EncoderDecoderProviderFactory::class,
            EncoderDecoderProviderFactory::class . '::addDefaultDecoders',
            'getEncoderDecoderProvider',
            EncoderDecoderProviderInterface::class
        ));
        $this->getEncoderDecoderProviderFactory()->getEncoderDecoderProvider();
        $this->getEncoderDecoderProviderFactory()->addDefaultDecoders();
    }

    /**
     * @return void
     * @throws EncoderDecoderProviderFactoryException
     * @throws DoubleException
     * @throws InterfaceNotFoundException
     * @throws ObjectProphecyException
     * @throws EncodeDecoderRegistryException
     */
    public function testAddEncoderWhenFrozenShouldThrowEncoderDecoderProviderFactoryException()
    {
        $this->expectExceptionObject(EncoderDecoderProviderFactoryException::factoryIsFrozen(
            EncoderDecoderProviderFactory::class,
            EncoderDecoderProviderFactory::class . '::addEncoder',
            'getEncoderDecoderProvider',
            EncoderDecoderProviderInterface::class
        ));
        $this->getEncoderDecoderProviderFactory()->getEncoderDecoderProvider();
        $this->getEncoderDecoderProviderFactory()->addEncoder('my-format', $this->prophesize(EncoderInterface::class)->reveal());
    }

    /**
     * @return void
     * @throws EncodeDecoderRegistryException
     * @throws EncoderDecoderProviderFactoryException
     * @throws InterfaceNotFoundException
     * @throws ObjectProphecyException
     * @throws DoubleException
     */
    public function testAddDecoderWhenFrozenShouldThrowEncoderDecoderProviderFactoryException()
    {
        $this->expectExceptionObject(EncoderDecoderProviderFactoryException::factoryIsFrozen(
            EncoderDecoderProviderFactory::class,
            EncoderDecoderProviderFactory::class . '::addDecoder',
            'getEncoderDecoderProvider',
            EncoderDecoderProviderInterface::class
        ));
        $this->getEncoderDecoderProviderFactory()->getEncoderDecoderProvider();
        $this->getEncoderDecoderProviderFactory()->addDecoder('my-format', $this->prophesize(DecoderInterface::class)->reveal());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws EncodeDecoderRegistryException
     */
    public function testCallGetEncoderDecoderProviderMoreThanOnceShouldReturnTheSameEncoderDecoderProvider(): void
    {
        $encoderDecoderProvider = $this->getEncoderDecoderProviderFactory()->getEncoderDecoderProvider();
        self::assertSame($encoderDecoderProvider, $this->getEncoderDecoderProviderFactory()->getEncoderDecoderProvider());
    }

    /**
     * @return void
     * @throws EncoderDecoderProviderFactoryException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws EncoderDecoderProviderException
     * @throws PhpUnitFrameworkException
     * @throws EncodeDecoderRegistryException
     */
    public function testGetEncoderDecoderProviderShouldReturnEncoderDecoderProviderWhichHasAllDefaultEncoderAndDecoders(): void
    {
        $this->getEncoderDecoderProviderFactory()->addDefaultEncoders();
        $this->getEncoderDecoderProviderFactory()->addDefaultDecoders();
        self::assertInstanceOf(JsonEncoder::class, $this->getEncoderDecoderProviderFactory()->getEncoderDecoderProvider()->getEncoder('json'));
        self::assertInstanceOf(YamlEncoder::class, $this->getEncoderDecoderProviderFactory()->getEncoderDecoderProvider()->getEncoder('yaml'));
        self::assertInstanceOf(YamlEncoder::class, $this->getEncoderDecoderProviderFactory()->getEncoderDecoderProvider()->getEncoder('yml'));
        self::assertInstanceOf(JsonDecoder::class, $this->getEncoderDecoderProviderFactory()->getEncoderDecoderProvider()->getDecoder('json'));
        self::assertInstanceOf(YamlDecoder::class, $this->getEncoderDecoderProviderFactory()->getEncoderDecoderProvider()->getDecoder('yaml'));
        self::assertInstanceOf(YamlDecoder::class, $this->getEncoderDecoderProviderFactory()->getEncoderDecoderProvider()->getDecoder('yml'));
    }

    /**
     * @return EncoderDecoderProviderFactory
     */
    private function getEncoderDecoderProviderFactory(): EncoderDecoderProviderFactory
    {
        if (null === $this->encoderDecoderProviderFactory) {
            $this->encoderDecoderProviderFactory = new EncoderDecoderProviderFactory();
        }

        return $this->encoderDecoderProviderFactory;
    }
}
