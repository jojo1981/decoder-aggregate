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
use Throwable;
use function sprintf;

/**
 * @package Jojo1981\DecoderAggregate\Exception
 */
final class EncoderDecoderProviderException extends DomainException
{
    /**
     * @param string $format
     * @param Throwable|null $previousException
     * @return EncoderDecoderProviderException
     */
    public static function couldNotGetEncoderForFormat(string $format, ?Throwable $previousException): EncoderDecoderProviderException
    {
        return new self(sprintf('Could not get a encoder for format: `%s`.', $format), 0, $previousException);
    }

    /**
     * @param string $format
     * @param Throwable|null $previousException
     * @return EncoderDecoderProviderException
     */
    public static function couldNotGetDecoderForFormat(string $format, ?Throwable $previousException): EncoderDecoderProviderException
    {
        return new self(sprintf('Could not get a decoder for for format: `%s`.', $format), 0, $previousException);
    }
}
