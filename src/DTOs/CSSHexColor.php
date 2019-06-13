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
use PHPExperts\SimpleDTO\SimpleDTO;

/**
 * @property-read string $hex
 */
final class CSSHexColor extends SimpleDTO implements Color
{
    public const MY_MIN = 3; // +1 for the '#' character.
    public const MY_MAX = 6; // +1 for the '#' character.

    public function __construct(string $hex, array $options = [], DataTypeValidator $validator = null)
    {
        $input = [
            'hex' => strtoupper($hex),
        ];

        parent::__construct($input, $options, $validator);
    }

    protected function extraValidation(array $input)
    {
        if (empty($input['hex']) || $input['hex'][0] !== '#') {
            throw new InvalidDataTypeException('Hex colors must begin with "#".');
        }

        $digits = strlen($input['hex']) - 1;
        if (!($digits === static::MY_MIN || $digits === static::MY_MAX)) {
            throw new InvalidDataTypeException(
                sprintf(
                    'Hex color codes must be %d or %d digits, not %d (%s)',
                    static::MY_MIN,
                    static::MY_MAX,
                    $digits,
                    $input['hex']
                )
            );
        }

        // Ensure that it is actually a valid CSS color code.
        self::assertIsValidCSSHexColor($input['hex']);
    }

    /**
     * Asserts whether a string is a valid CSS hexadecimal color code.
     * Taken from https://stackoverflow.com/a/12837990/430062.
     *
     * @param string $hex
     *
     * @return true if valid, otherwise an InvalidDataTpeException is thrown
     *
     * @throws InvalidDataTypeException if it fails the CSS regex test
     */
    public static function assertIsValidCSSHexColor(string $hex): bool
    {
        if (preg_match('/#([a-f0-9]{3}){1,2}\b/i', $hex) !== 1) {
            throw new InvalidDataTypeException("'$hex' is not a valid CSS hexadecimal color code.");
        }

        return true;
    }

    public function __toString(): string
    {
        return $this->hex;
    }
}
