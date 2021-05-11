<?php declare(strict_types=1);
/*
 * This file is part of the jojo1981/decoder-aggregate package
 *
 * Copyright (c) 2021 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\DecoderAggregate\Registry;

use Jojo1981\DecoderAggregate\DecoderInterface;
use Jojo1981\DecoderAggregate\EncodeDecoderRegistryInterface;
use Jojo1981\DecoderAggregate\EncoderInterface;
use Jojo1981\DecoderAggregate\Exception\EncodeDecoderRegistryException;
use function array_key_exists;

/**
 * @package Jojo1981\DecoderAggregate
 */
final class EncodeDecoderRegistry implements EncodeDecoderRegistryInterface
{
    /** @var EncoderInterface[] */
    private array $encoders = [];

    /** @var DecoderInterface[] */
    private array $decoders = [];

    /**
     * @param string $format
     * @param EncoderInterface $encoder
     * @return void
     * @throws EncodeDecoderRegistryException
     */
    public function registerEncoder(string $format, EncoderInterface $encoder): void
    {
        if (array_key_exists($format, $this->encoders)) {
            throw EncodeDecoderRegistryException::encoderForFormatAlreadyExists($format);
        }

        $this->encoders[$format] = $encoder;
    }

    /**
     * @param string $format
     * @param DecoderInterface $decoder
     * @return void
     * @throws EncodeDecoderRegistryException
     */
    public function registerDecoder(string $format, DecoderInterface $decoder): void
    {
        if (array_key_exists($format, $this->decoders)) {
            throw EncodeDecoderRegistryException::decoderForFormatAlreadyExists($format);
        }

        $this->decoders[$format] = $decoder;
    }

    /**
     * @param string $format
     * @return bool
     */
    public function hasEncoder(string $format): bool
    {
        return array_key_exists($format, $this->encoders);
    }

    /**
     * @param string $format
     * @return bool
     */
    public function hasDecoder(string $format): bool
    {
        return array_key_exists($format, $this->decoders);
    }

    /**
     * @param string $format
     * @return EncoderInterface
     * @throws EncodeDecoderRegistryException
     */
    public function getEncoder(string $format): EncoderInterface
    {
        if (!$this->hasEncoder($format)) {
            throw EncodeDecoderRegistryException::encoderForFormatDoesNotExists($format);
        }

        return $this->encoders[$format];
    }

    /**
     * @param string $format
     * @return DecoderInterface
     * @throws EncodeDecoderRegistryException
     */
    public function getDecoder(string $format): DecoderInterface
    {
        if (!$this->hasDecoder($format)) {
            throw EncodeDecoderRegistryException::decoderForFormatDoesNotExists($format);
        }

        return $this->decoders[$format];
    }
}
