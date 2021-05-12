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
use Jojo1981\DecoderAggregate\Exception\JsonDecodeException;
use Throwable;
use function define;
use function defined;
use function json_decode;
use function json_last_error;
use function json_last_error_msg;

if (!defined('JSON_THROW_ON_ERROR')) {
    define('JSON_THROW_ON_ERROR', 4194304);
}

/**
 * @package Jojo1981\DecoderAggregate\Decoder
 */
final class JsonDecoder implements DecoderInterface
{
    /** @var bool */
    private bool $associative;

    /** @var int */
    private int $depth;

    /** @var int */
    private int $flags;

    /**
     * @param bool $associative
     * @param int $depth
     * @param int $flags
     */
    public function __construct(bool $associative = false, int $depth = 512, int $flags = 0)
    {
        $this->associative = $associative;
        $this->depth = $depth;
        $this->flags = $flags;
    }

    /**
     * @param string $encodedString
     * @param array $options
     * @return mixed
     * @throws JsonDecodeException
     */
    public function decode(string $encodedString, array $options = [])
    {
        $options['associative'] = $options['associative'] ?? $this->associative;
        $options['depth'] = $options['depth'] ?? $this->depth;
        $options['flags'] = $options['flags'] ?? $this->flags;

        if (JSON_THROW_ON_ERROR === ($options['flags'] & JSON_THROW_ON_ERROR)) {
            $options['flags'] -= JSON_THROW_ON_ERROR;
        }

        try {
            $result = json_decode($encodedString, $options['associative'], $options['depth'], $options['flags'] + JSON_THROW_ON_ERROR);
        } catch (Throwable $exception) {
            throw new JsonDecodeException('Could not decode json string', 0, $exception);
        }

        if (0 !== $lastError = json_last_error()) {
            throw new JsonDecodeException(json_last_error_msg(), $lastError);
        }

        return $result;
    }
}
