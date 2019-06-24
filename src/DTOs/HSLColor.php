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
use PHPExperts\SimpleDTO\SimpleDTO;

/**
 * @see https://en.wikipedia.org/wiki/HSL_and_HSV
 *
 * @property-read int $hue
 * @property-read int $saturation
 * @property-read int $lightness
 */
final class HSLColor extends SimpleDTO
{
    private const HUE_MIN = 0;
    private const HUE_MAX = 359;
    private const CENT_MAX = 100;

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
                    $input[$geometry] = (int) substr($value, 0, -1);
                }
            }
        };

        $convertFromPercents();

        parent::__construct($input, $options, $validator);
    }

    protected function extraValidation(array $input)
    {
        $reasons = [];
        if ($input['hue'] < self::HUE_MIN || $input['hue'] > self::HUE_MAX) {
            $reasons['hue'] = sprintf(
                'Must be between %d and %d, not %d',
                self::HUE_MIN,
                self::HUE_MAX,
                $input['hue']
            );
        }

        foreach (['saturation', 'lightness'] as $geometry) {
            if ($input[$geometry] < self::HUE_MIN || $input[$geometry] > self::CENT_MAX) {
                $reasons[$geometry] = sprintf(
                    'Must be between %d and %d, not %d',
                    0,
                    100,
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
            $this->saturation,
            $this->lightness
        );
    }
}
