<?php declare(strict_types=1);
/*
 * This file is part of the jojo1981/decoder-aggregate package
 *
 * Copyright (c) 2021 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\DecoderAggregate\Provider;

use Jojo1981\DecoderAggregate\DecoderInterface;
use Jojo1981\DecoderAggregate\EncodeDecoderRegistryInterface;
use Jojo1981\DecoderAggregate\EncoderDecoderProviderInterface;
use Jojo1981\DecoderAggregate\EncoderInterface;
use Jojo1981\DecoderAggregate\Exception\EncodeDecoderRegistryException;
use Jojo1981\DecoderAggregate\Exception\EncoderDecoderProviderException;

/**
 * @package Jojo1981\DecoderAggregate\Provider
 */
final class EncoderDecoderProvider implements EncoderDecoderProviderInterface
{
    /** @var EncodeDecoderRegistryInterface */
    private EncodeDecoderRegistryInterface $encodeDecoderRegistry;

    /**
     * @param EncodeDecoderRegistryInterface $encodeDecoderRegistry
     */
    public function __construct(EncodeDecoderRegistryInterface $encodeDecoderRegistry)
    {
        $this->encodeDecoderRegistry = $encodeDecoderRegistry;
    }

    /**
     * @param string $format
     * @return EncoderInterface
     * @throws EncoderDecoderProviderException
     */
    public function getEncoder(string $format): EncoderInterface
    {
        try {
            return $this->encodeDecoderRegistry->getEncoder($format);
        } catch (EncodeDecoderRegistryException $exception) {
            throw EncoderDecoderProviderException::couldNotGetEncoderForFormat($format, $exception);
        }
    }

    /**
     * @param string $format
     * @return DecoderInterface
     * @throws EncoderDecoderProviderException
     */
    public function getDecoder(string $format): DecoderInterface
    {
        try {
            return $this->encodeDecoderRegistry->getDecoder($format);
        } catch (EncodeDecoderRegistryException $exception) {
            throw EncoderDecoderProviderException::couldNotGetDecoderForFormat($format, $exception);
        }
    }
}
