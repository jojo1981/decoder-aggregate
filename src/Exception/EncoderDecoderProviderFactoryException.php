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
final class EncoderDecoderProviderFactoryException extends DomainException
{
    /**
     * @param string $className
     * @param string $methodName
     * @param string $buildMethod
     * @param string $buildClassName
     * @return EncoderDecoderProviderFactoryException
     */
    public static function factoryIsFrozen(
        string $className,
        string $methodName,
        string $buildMethod,
        string $buildClassName
    ): EncoderDecoderProviderFactoryException {
        return new self(sprintf(
            'Factory: %s is frozen, method: %s can not be called, because method: %s is already called and a %s is already build.',
            $className,
            $methodName,
            $buildMethod,
            $buildClassName
        ));
    }
}
