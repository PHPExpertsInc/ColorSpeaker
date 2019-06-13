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
         * Taken from https://stackoverflow.com/a/13887939/430062
         *
         * Copyright © 2012 https://stackoverflow.com/users/629493/unsigned
         * Licensed under the terms of the BSD License.
         * (Basically, this means you can do whatever you like with it,
         *   but if you just copy and paste my code into your app, you
         *   should give me a shout-out/credit :)
         *
         * @param int $R Red
         * @param int $G Green
         * @param int $B Blue
         * @return float[]
         */
        $RGBtoHSV = function(int $R, int $G, int $B)
        {
            // RGB values:    0-255, 0-255, 0-255
            // HSV values:    0-360, 0-100, 0-100

            // Convert the RGB byte-values to percentages
            $R = ($R / 255);
            $G = ($G / 255);
            $B = ($B / 255);

            // Calculate a few basic values, the maximum value of R,G,B, the
            //   minimum value, and the difference of the two (chroma).
            $maxRGB = max($R, $G, $B);
            $minRGB = min($R, $G, $B);
            $chroma = $maxRGB - $minRGB;

            // Value (also called Brightness) is the easiest component to calculate,
            //   and is simply the highest value among the R,G,B components.
            // We multiply by 100 to turn the decimal into a readable percent value.
            $computedV = 100 * $maxRGB;

            // Special case if hueless (equal parts RGB make black, white, or grays)
            // Note that Hue is technically undefined when chroma is zero, as
            //   attempting to calculate it would cause division by zero (see
            //   below), so most applications simply substitute a Hue of zero.
            // Saturation will always be zero in this case, see below for details.
            if ($chroma == 0)
                return array(0, 0, $computedV);

            // Saturation is also simple to compute, and is simply the chroma
            //   over the Value (or Brightness)
            // Again, multiplied by 100 to get a percentage.
            $computedS = 100 * ($chroma / $maxRGB);

            // Calculate Hue component
            // Hue is calculated on the "chromacity plane", which is represented
            //   as a 2D hexagon, divided into six 60-degree sectors. We calculate
            //   the bisecting angle as a value 0 <= x < 6, that represents which
            //   portion of which sector the line falls on.
            if ($R == $minRGB)
                $h = 3 - (($G - $B) / $chroma);
            elseif ($B == $minRGB)
                $h = 1 - (($R - $G) / $chroma);
            else // $G == $minRGB
                $h = 5 - (($B - $R) / $chroma);

            // After we have the sector position, we multiply it by the size of
            //   each sector's arc (60 degrees) to obtain the angle in degrees.
            $computedH = 60 * $h;

            return array($computedH, $computedS, $computedV);
        };

        $hslArray = $RGBtoHSV($rgbColor->red, $rgbColor->green, $rgbColor->blue);

        // Convert the whole percents into proper fractions.
        $hslArray[1] /= 100.00;
        $hslArray[2] /= 100.00;

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
         * Taken from https://gist.github.com/vkbo/2323023.
         *
         * @param int   $hue
         * @param float $dS
         * @param float $dV
         * @return array
         */
        $convertFromHSLToRGB = function(int $hue, float $dS, float $dV): array
        {
            $dC = $dV * $dS;   // Chroma:     0.0-1.0
            $dH = $hue / 60.0; // H-Prime:    0.0-6.0
            $dT = $dH;         // Temp variable

            while($dT >= 2.0) $dT -= 2.0;   // php modulus does not work with float

            $dX = $dC * (1 - abs($dT - 1)); // as used in the Wikipedia link

            switch(floor($dH)) {
                case 0:  $dR = $dC; $dG = $dX; $dB = 0.0; break;
                case 1:  $dR = $dX; $dG = $dC; $dB = 0.0; break;
                case 2:  $dR = 0.0; $dG = $dC; $dB = $dX; break;
                case 3:  $dR = 0.0; $dG = $dX; $dB = $dC; break;
                case 4:  $dR = $dX; $dG = 0.0; $dB = $dC; break;
                case 5:  $dR = $dC; $dG = 0.0; $dB = $dX; break;
                default: $dR = 0.0; $dG = 0.0; $dB = 0.0; break;
            }

            $dM  = $dV - $dC;
            $dR += $dM; $dG += $dM; $dB += $dM;
            $dR *= 255; $dG *= 255; $dB *= 255;

            return [min(round($dR), 255), min(round($dG), 255), min(round($dB), 255)];
        };

        $rgbColor = new RGBColor($convertFromHSLToRGB(
            $this->color->hue,
            $this->color->saturation,
            $this->color->lightness
        ));

        return $rgbColor;
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
