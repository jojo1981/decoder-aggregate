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
    /** @var int */
    private int $inline;

    /** @var int */
    private int $indent;

    /** @var int */
    private int $flags;

    /**
     * @param int $inline
     * @param int $indent
     * @param int $flags
     */
    public function __construct(int $inline = 2, int $indent = 4, int $flags = 0)
    {
        $this->inline = $inline;
        $this->indent = $indent;
        $this->flags = $flags;
    }

    /**
     * @param mixed $data
     * @param array $options
     * @return string
     * @throws YamlEncoderException
     */
    public function encode($data, array $options = []): string
    {
        $options['inline'] = $options['inline'] ?? $this->inline;
        $options['indent'] = $options['indent'] ?? $this->indent;
        $options['flags'] = $options['flags'] ?? $this->flags;

        try {
            return Yaml::dump($data, $options['inline'], $options['indent'], $options['flags']);
        } catch (Throwable $exception) {
            throw new YamlEncoderException('Could not encode data into a yaml string', 0, $exception);
        }
    }
}
