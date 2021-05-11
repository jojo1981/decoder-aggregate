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
use Jojo1981\DecoderAggregate\Exception\JsonEncoderException;
use Throwable;
use function defined;
use function json_encode;
use function json_last_error;
use function json_last_error_msg;

if (!defined('JSON_THROW_ON_ERROR')) {
    define('JSON_THROW_ON_ERROR', 4194304);
}

/**
 * @package Jojo1981\DecoderAggregate\Encoder
 */
final class JsonEncoder implements EncoderInterface
{
    /** @var int */
    private int $flags;

    /** @var int */
    private int $depth;

    /**
     * @param int $flags
     * @param int $depth
     */
    public function __construct(int $flags = 0, int $depth = 512)
    {
        $this->flags = $flags;
        $this->depth = $depth;
    }

    /**
     * @param mixed $data
     * @param array $options
     * @return string
     * @throws JsonEncoderException
     */
    public function encode($data, array $options = []): string
    {
        $options['flags'] = $options['flags'] ?? $this->flags;
        $options['depth'] = $options['depth'] ?? $this->depth;
        if (JSON_THROW_ON_ERROR === ($options['flags'] & JSON_THROW_ON_ERROR)) {
            $options['flags'] -= JSON_THROW_ON_ERROR;
        }

        try {
            $result = json_encode($data, $options['flags'] + JSON_THROW_ON_ERROR, $options['depth']);
        } catch (Throwable $exception) {
            throw new JsonEncoderException('Could not encode data into a json string', 0, $exception);
        }

        if ((false === $result) && 0 !== ($lastError = json_last_error())) {
            throw new JsonEncoderException(json_last_error_msg(), $lastError);
        }

        return $result;
    }
}
