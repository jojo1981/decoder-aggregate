<?php declare(strict_types=1);
/*
 * This file is part of the jojo1981/decoder-aggregate package
 *
 * Copyright (c) 2021 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\DecoderAggregate\Encoder;

use Jojo1981\DecoderAggregate\EncoderInterface;
use Jojo1981\DecoderAggregate\Exception\YamlEncoderException;
use Symfony\Component\Yaml\Yaml;
use Throwable;

/**
 * @package Jojo1981\DecoderAggregate\Encoder
 */
final class YamlEncoder implements EncoderInterface
{
    /**
     * @param mixed $data
     * @param array $options
     * @return string
     * @throws YamlEncoderException
     */
    public function encode($data, array $options = []): string
    {
        $options['inline'] = $options['inline'] ?? 2;
        $options['indent'] = $options['indent'] ?? 4;
        $options['flags'] = $options['flags'] ?? 0;

        try {
            return Yaml::dump($data, $options['inline'], $options['indent'], $options['flags']);
        } catch (Throwable $exception) {
            throw new YamlEncoderException('Could not encode data into a yaml string', 0, $exception);
        }
    }
}
