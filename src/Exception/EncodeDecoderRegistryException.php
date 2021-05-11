<?php declare(strict_types=1);
/*
 * This file is part of the jojo1981/decoder-aggregate package
 *
 * Copyright (c) 2021 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\DecoderAggregate\Exception;

use DomainException;
use function sprintf;

/**
 * @package Jojo1981\DecoderAggregate\Exception
 */
final class EncodeDecoderRegistryException extends DomainException
{
    /**
     * @param string $format
     * @return EncodeDecoderRegistryException
     */
    public static function encoderForFormatAlreadyExists(string $format): EncodeDecoderRegistryException
    {
        return new self(sprintf('There is already a encoder register for format: `%s`.', $format));
    }

    /**
     * @param string $format
     * @return EncodeDecoderRegistryException
     */
    public static function decoderForFormatAlreadyExists(string $format): EncodeDecoderRegistryException
    {
        return new self(sprintf('There is already a decoder register for format: `%s`.', $format));
    }

    /**
     * @param string $format
     * @return EncodeDecoderRegistryException
     */
    public static function encoderForFormatDoesNotExists(string $format): EncodeDecoderRegistryException
    {
        return new self(sprintf('There is no encoder registered for format: `%s`.', $format));
    }

    /**
     * @param string $format
     * @return EncodeDecoderRegistryException
     */
    public static function decoderForFormatDoesNotExists(string $format): EncodeDecoderRegistryException
    {
        return new self(sprintf('There is no decoder registered for format: `%s`.', $format));
    }
}
