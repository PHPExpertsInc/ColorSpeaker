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

namespace PHPExperts\ColorSpeaker;

use PHPExperts\ColorSpeaker\DTOs\RGBColor;

class RGBSpeaker
{
    /** @var RGBColor */
    private $rgbColor;

    public function __construct($red, $green, $blue)
    {
        $this->rgbColor = new RGBColor(['red' => $red, 'blue' => $blue, 'green' => $green]);
    }

    public static function fromRGB(RGBColor $rgbColor): self
    {
        return new self($rgbColor->red, $rgbColor->green, $rgbColor->blue);
    }

    public function __toString(): string
    {
        return $this->rgbColor->__toString();
    }

    public function toRGB(): RGBColor
    {
        return $this->rgbColor;
    }

    /**
     * Taken from https://stackoverflow.com/a/32977705/430062.
     *
     * @param bool $uppercase
     *
     * @return string
     */
    public function toHex(bool $uppercase = true): string
    {
        $hex = sprintf('#%02x%02x%02x', $this->rgbColor->red, $this->rgbColor->green, $this->rgbColor->blue);
        if ($uppercase === true) {
            $hex = strtoupper($hex);
        }

        return $hex;
    }
}
