<?php declare(strict_types=1);

/**
 * This file is part of ColorSpeaker, a PHP Experts, Inc., Project.
 *
 * Copyright © 2019 PHP Experts, Inc.
 * Author: Theodore R. Smith <theodore@phpexperts.pro>
 *   GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690
 *   https://www.phpexperts.pro/
 *   https://github.com/PHPExpertsInc/RGBSpeaker
 *
 * This file is licensed under the MIT License.
 *
 * It is inspired by https://stitcher.io/blog/tests-and-types
 *                   http://archive.is/99WyU
 */

namespace PHPExperts\ColorSpeaker\DTOs;

use PHPExperts\DataTypeValidator\InvalidDataTypeException;

trait MinMax
{
    public function extraValidation(array $input)
    {
        $reasons = [];
        foreach ($input as $color => $value) {
            if ($value < static::MY_MIN) {
                $reasons[$color] = sprintf('Must be greater than or equal to %s, not %d', static::MY_MIN, $value);
            } elseif ($value > static::MY_MAX) {
                $reasons[$color] = sprintf('Must be lesser than or equal to %s, not %d', static::MY_MAX, $value);
            }
        }

        if (!empty($reasons)) {
            throw new InvalidDataTypeException(
                sprintf('Color values must be between %s and %s, inclusive.', static::MY_MIN, static::MY_MAX),
                $reasons
            );
        }
    }
}
