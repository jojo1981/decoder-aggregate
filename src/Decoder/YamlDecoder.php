<?php declare(strict_types=1);
/*
 * This file is part of the jojo1981/decoder-aggregate package
 *
 * Copyright (c) 2021 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\DecoderAggregate\Decoder;

use Jojo1981\DecoderAggregate\DecoderInterface;
use Jojo1981\DecoderAggregate\Exception\YamlDecodeException;
use Symfony\Component\Yaml\Yaml;
use Throwable;

/**
 * @package Jojo1981\DecoderAggregate\Decoder
 */
final class YamlDecoder implements DecoderInterface
{
    /**
     * @param string $encodedString
     * @param array $options
     * @return array
     * @throws YamlDecodeException
     */
    public function decode(string $encodedString, array $options = []): array
    {
        $options['flags'] = $options['flags'] ?? 0;

        try {
            return Yaml::parse($encodedString, $options['flags']);
        } catch (Throwable $exception) {
            throw new YamlDecodeException('Could not decode yaml string', 0, $exception);
        }
    }
}
