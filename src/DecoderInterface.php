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

use Jojo1981\DecoderAggregate\Exception\DecoderException;

/**
 * @package Jojo1981\DecoderAggregate
 */
interface DecoderInterface
{
    /**
     * @param string $encodedString
     * @param array $options
     * @return mixed
     * @throws DecoderException
     */
    public function decode(string $encodedString, array $options = []);
}
