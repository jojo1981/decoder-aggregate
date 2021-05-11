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
use Jojo1981\DecoderAggregate\Provider\EncoderDecoderProvider;
use Jojo1981\DecoderAggregate\Registry\EncodeDecoderRegistry;

/**
 * @package Jojo1981\DecoderAggregate\Factory
 */
final class EncoderDecoderProviderFactory
{
    /** @var EncodeDecoderRegistryInterface */
    private EncodeDecoderRegistryInterface $encodeDecoderRegistry;

    /**
     * @param EncodeDecoderRegistryInterface|null $encodeDecoderRegistry
     */
    public function __construct(?EncodeDecoderRegistryInterface $encodeDecoderRegistry = null)
    {
        $this->encodeDecoderRegistry = $encodeDecoderRegistry ?? new EncodeDecoderRegistry();
    }

    /**
     * @return void
     * @throws EncodeDecoderRegistryException
     */
    public function addDefaultEncoders(): void
    {
        $yamlEncoder = new YamlEncoder();
        if (!$this->encodeDecoderRegistry->hasEncoder('json')) {
            $this->encodeDecoderRegistry->registerEncoder('json', new JsonEncoder());
        }
        if (!$this->encodeDecoderRegistry->hasEncoder('yaml')) {
            $this->encodeDecoderRegistry->registerEncoder('yaml', $yamlEncoder);
        }
        if (!$this->encodeDecoderRegistry->hasEncoder('yml')) {
            $this->encodeDecoderRegistry->registerEncoder('yml', $yamlEncoder);
        }
    }

    /**
     * @return void
     * @throws EncodeDecoderRegistryException
     */
    public function addDefaultDecoders(): void
    {
        $yamlDecoder = new YamlDecoder();
        if (!$this->encodeDecoderRegistry->hasDecoder('json')) {
            $this->encodeDecoderRegistry->registerDecoder('json', new JsonDecoder());
        }
        if (!$this->encodeDecoderRegistry->hasDecoder('yaml')) {
            $this->encodeDecoderRegistry->registerDecoder('yaml', $yamlDecoder);
        }
        if (!$this->encodeDecoderRegistry->hasDecoder('yml')) {
            $this->encodeDecoderRegistry->registerDecoder('yml', $yamlDecoder);
        }
    }

    /**
     * @param string $format
     * @param EncoderInterface $encoder
     * @return void
     * @throws EncodeDecoderRegistryException
     */
    public function addEncoder(string $format, EncoderInterface $encoder): void
    {
        $this->encodeDecoderRegistry->registerEncoder($format, $encoder);
    }

    /**
     * @param string $format
     * @param DecoderInterface $decoder
     * @return void
     * @throws EncodeDecoderRegistryException
     */
    public function addDecoder(string $format, DecoderInterface $decoder): void
    {
        $this->encodeDecoderRegistry->registerDecoder($format, $decoder);
    }

    /**
     * @return EncoderDecoderProviderInterface
     */
    public function getEncoderDecoderProvider(): EncoderDecoderProviderInterface
    {
        return new EncoderDecoderProvider($this->encodeDecoderRegistry);
    }
}
