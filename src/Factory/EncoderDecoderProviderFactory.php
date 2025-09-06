<?php declare(strict_types=1);
/*
 * This file is part of the jojo1981/decoder-aggregate package
 *
 * Copyright (c) 2021 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\DecoderAggregate\Factory;

use Jojo1981\DecoderAggregate\Decoder\JsonDecoder;
use Jojo1981\DecoderAggregate\Decoder\YamlDecoder;
use Jojo1981\DecoderAggregate\DecoderInterface;
use Jojo1981\DecoderAggregate\EncodeDecoderRegistryInterface;
use Jojo1981\DecoderAggregate\Encoder\JsonEncoder;
use Jojo1981\DecoderAggregate\Encoder\YamlEncoder;
use Jojo1981\DecoderAggregate\EncoderDecoderProviderInterface;
use Jojo1981\DecoderAggregate\EncoderInterface;
use Jojo1981\DecoderAggregate\Exception\EncodeDecoderRegistryException;
use Jojo1981\DecoderAggregate\Exception\EncoderDecoderProviderFactoryException;
use Jojo1981\DecoderAggregate\Provider\EncoderDecoderProvider;
use Jojo1981\DecoderAggregate\Registry\EncodeDecoderRegistry;
use function array_key_exists;

/**
 * @package Jojo1981\DecoderAggregate\Factory
 */
final class EncoderDecoderProviderFactory
{
    /** @var EncodeDecoderRegistryInterface|null */
    private ?EncodeDecoderRegistryInterface $encodeDecoderRegistry = null;

    /** @var EncoderDecoderProviderInterface|null */
    private ?EncoderDecoderProviderInterface $encoderDecoderProvider = null;

    /** @var bool */
    private bool $frozen = false;

    /** @var EncoderInterface[] */
    private array $encoders = [];

    /** @var DecoderInterface[] */
    private array $decoders = [];

    /**
     * @return void
     * @throws EncoderDecoderProviderFactoryException
     */
    public function addDefaultEncoders(): void
    {
        $this->assertFrozen(__METHOD__);

        $yamlEncoder = new YamlEncoder();
        if (!array_key_exists('json', $this->encoders)) {
            $this->encoders['json'] = new JsonEncoder();
        }
        if (!array_key_exists('yaml', $this->encoders)) {
            $this->encoders['yaml'] = $yamlEncoder;
        }
        if (!array_key_exists('yml', $this->encoders)) {
            $this->encoders['yml'] = $yamlEncoder;
        }
    }

    /**
     * @return void
     * @throws EncoderDecoderProviderFactoryException
     */
    public function addDefaultDecoders(): void
    {
        $this->assertFrozen(__METHOD__);

        $yamlDecoder = new YamlDecoder();
        if (!array_key_exists('json', $this->decoders)) {
            $this->decoders['json'] = new JsonDecoder();
        }
        if (!array_key_exists('yaml', $this->decoders)) {
            $this->decoders['yaml'] = $yamlDecoder;
        }
        if (!array_key_exists('yml', $this->decoders)) {
            $this->decoders['yml'] = $yamlDecoder;
        }
    }

    /**
     * @param string $format
     * @param EncoderInterface $encoder
     * @return void
     * @throws EncoderDecoderProviderFactoryException
     */
    public function addEncoder(string $format, EncoderInterface $encoder): void
    {
        $this->assertFrozen(__METHOD__);
        $this->encoders[$format] = $encoder;
    }

    /**
     * @param string $format
     * @param DecoderInterface $decoder
     * @return void
     * @throws EncoderDecoderProviderFactoryException
     */
    public function addDecoder(string $format, DecoderInterface $decoder): void
    {
        $this->assertFrozen(__METHOD__);
        $this->decoders[$format] = $decoder;
    }

    /**
     * @return EncoderDecoderProviderInterface
     * @throws EncodeDecoderRegistryException
     */
    public function getEncoderDecoderProvider(): EncoderDecoderProviderInterface
    {
        if (null === $this->encoderDecoderProvider) {
            $this->frozen = true;
            $this->encoderDecoderProvider = new EncoderDecoderProvider($this->getEncodeDecoderRegistry());
        }

        return $this->encoderDecoderProvider;
    }

    /**
     * @return EncodeDecoderRegistryInterface
     * @throws EncodeDecoderRegistryException
     */
    private function getEncodeDecoderRegistry(): EncodeDecoderRegistryInterface
    {
        if (null === $this->encodeDecoderRegistry) {
            $this->encodeDecoderRegistry = new EncodeDecoderRegistry();
            foreach ($this->encoders as $format => $encoder) {
                $this->encodeDecoderRegistry->registerEncoder($format, $encoder);
            }
            foreach ($this->decoders as $format => $decoder) {
                $this->encodeDecoderRegistry->registerDecoder($format, $decoder);
            }
        }

        return $this->encodeDecoderRegistry;
    }

    /**
     * @param string $calledMethodName
     * @return void
     * @throws EncoderDecoderProviderFactoryException
     */
    private function assertFrozen(string $calledMethodName): void
    {
        if ($this->frozen) {
            throw EncoderDecoderProviderFactoryException::factoryIsFrozen(
                __CLASS__,
                $calledMethodName,
                'getEncoderDecoderProvider',
                EncoderDecoderProviderInterface::class
            );
        }
    }
}
