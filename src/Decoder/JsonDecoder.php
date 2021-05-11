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
    /**
     * @param string $encodedString
     * @param array $options
     * @return array
     * @throws JsonDecodeException
     */
    public function decode(string $encodedString, array $options = []): array
    {
        $options['associative'] = $options['associative'] ?? false;
        $options['depth'] = $options['depth'] ?? 512;
        $options['flags'] = $options['flags'] ?? JSON_THROW_ON_ERROR;
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
