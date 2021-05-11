<?php declare(strict_types=1);
/*
 * This file is part of the jojo1981/decoder-aggregate package
 *
 * Copyright (c) 2021 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\DecoderAggregate;

use Jojo1981\DecoderAggregate\Exception\EncodeDecoderRegistryException;

/**
 * @package Jojo1981\DecoderAggregate
 */
interface EncodeDecoderRegistryInterface
{
    /**
     * @param string $format
     * @param EncoderInterface $encoder
     * @return void
     * @throws EncodeDecoderRegistryException
     */
    public function registerEncoder(string $format, EncoderInterface $encoder): void;

    /**
     * @param string $format
     * @param DecoderInterface $decoder
     * @return void
     * @throws EncodeDecoderRegistryException
     */
    public function registerDecoder(string $format, DecoderInterface $decoder): void;

    /**
     * @param string $format
     * @return bool
     */
    public function hasEncoder(string $format): bool;

    /**
     * @param string $format
     * @return bool
     */
    public function hasDecoder(string $format): bool;

    /**
     * @param string $format
     * @return EncoderInterface
     * @throws EncodeDecoderRegistryException
     */
    public function getEncoder(string $format): EncoderInterface;

    /**
     * @param string $format
     * @return DecoderInterface
     * @throws EncodeDecoderRegistryException
     */
    public function getDecoder(string $format): DecoderInterface;
}
