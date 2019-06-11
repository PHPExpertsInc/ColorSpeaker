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
use PHPExperts\SimpleDTO\SimpleDTO;

/**
 * @property-read int $red
 * @property-read int $green
 * @property-read int $blue
 */
final class RGBColor extends SimpleDTO
{
    use MinMax;

    public const MY_MIN = 0;
    public const MY_MAX = 255;

    public function __construct(array $input, array $options = [], DataTypeValidator $validator = null)
    {
        // Convert regular array to a properly keyed map.
        if (array_keys($input) === [0, 1, 2]) {
            $input = [
                'red'   => $input[0],
                'green' => $input[1],
                'blue'  => $input[2],
            ];
        }

        parent::__construct($input, $options, $validator);
    }

    public function __toString(): string
    {
        return sprintf(
            'rgb(%d, %d, %d)',
            $this->red,
            $this->green,
            $this->blue
        );
    }
}
