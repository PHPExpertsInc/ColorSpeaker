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

use PHPExperts\DataTypeValidator\DataTypeValidator;
use PHPExperts\DataTypeValidator\InvalidDataTypeException;
use PHPExperts\DataTypeValidator\IsAFuzzyDataType;
use PHPExperts\DataTypeValidator\IsAStrictDataType;
use PHPExperts\SimpleDTO\SimpleDTO;

/**
 * @see https://en.wikipedia.org/wiki/HSL_and_HSV
 *
 * @property-read int   $hue
 * @property-read float $saturation
 * @property-read float $lightness
 */
final class HSLColor extends SimpleDTO
{
    private const HUE_MIN = 0;
    private const HUE_MAX = 360;

    /** @var DataTypeValidator */
    private $validator;

    public function __construct(array $input, array $options = [self::PERMISSIVE], DataTypeValidator $validator = null)
    {
        if (!$validator) {
            $isA = new IsAFuzzyDataType();
            $validator = new DataTypeValidator($isA);
        }
        $this->validator = $validator;

        $this->validator = $validator;
        // Convert regular array to a properly keyed map.
        if (array_keys($input) === [0, 1, 2]) {
            $input = [
                'hue'        => $input[0],
                'saturation' => $input[1],
                'lightness'  => $input[2],
            ];
        }

        // Converts from either '99.5%' or '99.5' to '0.995'
        $convertFromPercents = function() use (&$input) {
            foreach (['saturation', 'lightness'] as $geometry) {
                $value = $input[$geometry];
                // Strip off the '%', if it's present.
                if (is_string($value) && substr($value, -1) === '%') {
                    $value = substr($value, 0, -1);
                }

                if ($this->validator->isInt($value)) {
                    $input[$geometry] = floatval($value) / 100.00;
                }
            }
        };

        $convertFromPercents();
//        dd($input);

        parent::__construct($input, $options, $validator);
    }

    protected function extraValidation(array $input)
    {
        $reasons = [];
        if ($input['hue'] < self::HUE_MIN || $input['hue'] > self::HUE_MAX) {
            $reasons[] = sprintf(
                'HSLColor\'s Hue must be between %d and %d, not %d',
                self::HUE_MIN,
                self::HUE_MAX,
                $input['hue']
            );
        }

        foreach (['saturation', 'lightness'] as $geometry) {
            if ($input[$geometry] < 0.00 || $input[$geometry] > 1.00) {
                $reasons[] = sprintf(
                    "HSLColor's $geometry must be between %0.2f and %0.2f, not %0.2f",
                    0.00,
                    1.00,
                    $input[$geometry]
                );
            }
        }

        if (!empty($reasons)) {
            throw new InvalidDataTypeException('Invalid HSL geometry.', $reasons);
        }

    }

    public function __toString(): string
    {
        return sprintf(
            'hsl(%d, %d%%, %d%%)',
            $this->hue,
            $this->saturation * 100,
            $this->lightness * 100
        );
    }
}
