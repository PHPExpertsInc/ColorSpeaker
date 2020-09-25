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

namespace PHPExperts\ColorSpeaker\internal;

use PHPExperts\ColorSpeaker\DTOs\CSSHexColor;
use PHPExperts\ColorSpeaker\DTOs\HSLColor;
use PHPExperts\ColorSpeaker\DTOs\RGBColor;

/** @internal */
final class HSLSpeaker implements ColorSpeakerContract
{
    /** @var HSLColor */
    private $color;

    public function __construct(HSLColor $hslColor)
    {
        $this->color = $hslColor;
    }

    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     *
     * @return HSLSpeaker
     */
    public static function fromRGB(int $red, int $green, int $blue): ColorSpeakerContract
    {
        // Convert to an RGBColor first to ensure it is, indeed, a valid RGB Color.
        $rgbColor = new RGBColor(['red' => $red, 'green' => $green, 'blue' => $blue]);

        /**
         * Taken from https://stackoverflow.com/a/9493060/430062 plus comments.
         *
         * @param int $r Red
         * @param int $g Green
         * @param int $b Blue
         * @return array
         */
        $rgbToHSL = function(int $r, int $g, int $b): array
        {
            $max = max($r, $g, $b);
            $min = min($r, $g, $b);
            $r /= 255; $g /= 255; $b /= 255;
            $h = $s = $l = 0;
            $l = ($max + $min) / 2 / 255;

            if ($max === $min) {
                $h = $s = 0;
            } else {
                $min /= 255;
                $max /= 255;

                $d = ($max - $min);
                $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
                switch ($max) {
                    case $r: $h = (($g - $b) / $d + 0) * 60; break;
                    case $g: $h = (($b - $r) / $d + 2) * 60; break;
                    case $b: $h = (($r - $g) / $d + 4) * 60; break;
                }
            }

            return [$h, $s, $l];
        };

        $hslArray = $rgbToHSL($rgbColor->red, $rgbColor->green, $rgbColor->blue);

        /*
         *    x AAA AAA AAA x
         *   x AAA   A   AAA x
         *  x A   AAA AAA   A x
         * x AAA AAA   AAA AAA x
         *x A   A   AAA   A   A x
         * AAA AAA AAAAA AAA AAA x
         * vvv vvv vvvvv vvv vvv x
         *x v   v   vvv   v   v x
         * x vvv vvv   vvv vvv x
         *  x v   vvv vvv   v x
         *   x vvv   v   vvv x
         *    x vvv vvv vvv x
         *     x x x x x x x
         */

        // Convert the floats into cents.
        $hslArray[0] = (int) min(round($hslArray[0]), 359);
        $hslArray[1] = (int) round($hslArray[1] * 100);
        $hslArray[2] = (int) round($hslArray[2] * 100);

        $hslColor = new HSLColor($hslArray);

        return new self($hslColor);
    }

    /**
     * @param string $hex
     *
     * @return HSLSpeaker
     */
    public static function fromHexCode(string $hex): ColorSpeakerContract
    {
        $rgbColor = CSSHexSpeaker::fromHexCode($hex)->toRGB();
        $hslColor = self::fromRGB($rgbColor->red, $rgbColor->green, $rgbColor->blue)->toHSL();

        return new self($hslColor);
    }

    /**
     * @param int          $hue
     * @param float|string $saturation Either float or "55.5%"
     * @param float|string $lightness  Either float or "55.5%"
     * @return ColorSpeakerContract
     */
    public static function fromHSL(int $hue, $saturation, $lightness): ColorSpeakerContract
    {
        $hslColor = new HSLColor([$hue, $saturation, $lightness]);

        return new self($hslColor);
    }

    public function __toString(): string
    {
        return $this->color->__toString();
    }

    public function toRGB(): RGBColor
    {
        /**
         * Taken from https://stackoverflow.com/a/40492904/430062 with a few mods.
         *
         * @param int $h
         * @param int $s
         * @param int $l
         * @return array
         */
        $hslToRGB = function (int $h, int $s, int $l): array
        {
            $h /= 360.00;
            $s /= 100.00;
            $l /= 100.00;

            $hueToRGB = function ($p, $q, $t): int {
                if ($t < 0) { $t += 1; }
                if ($t > 1) { $t -= 1; }
                if ($t < 1/6) { $value = $p + ($q - $p) * 6 * $t; }
                elseif ($t < 1/2) { $value = $q; }
                elseif ($t < 2/3) { $value = $p + ($q - $p) * (2/3 - $t) * 6; }
                else { $value = $p; }

                return (int) round($value * 255);
            };

            // Achromatic.
            if ($s === 0.0) {
                $r = $g = $b = (int) ($l * 255);
            } else {
                $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
                $p = 2 * $l - $q;
                $r = $hueToRGB($p, $q, $h + 1/3);
                $g = $hueToRGB($p, $q, $h);
                $b = $hueToRGB($p, $q, $h - 1/3);
            }

            return [$r, $g, $b];
        };

        return new RGBColor($hslToRGB(
            $this->color->hue,
            $this->color->saturation,
            $this->color->lightness
        ));
    }

    public function toHexCode(): CSSHexColor
    {
        $rgbColor = $this->toRGB();

        return CSSHexSpeaker::fromRGB($rgbColor->red, $rgbColor->green, $rgbColor->blue)->toHexCode();
    }

    public function toHSL(): HSLColor
    {
        return $this->color;
    }
}
