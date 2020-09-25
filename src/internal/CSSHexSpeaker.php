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
final class CSSHexSpeaker implements ColorSpeakerContract
{
    /** @var CSSHexColor */
    private $color;

    public function __construct(CSSHexColor $hexColor)
    {
        $this->color = $hexColor;
    }

    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     *
     * @return CSSHexSpeaker
     */
    public static function fromRGB(int $red, int $green, int $blue): ColorSpeakerContract
    {
        $rgbColor = new RGBColor([$red, $green, $blue]);
        $hex = sprintf('#%02x%02x%02x', $rgbColor->red, $rgbColor->green, $rgbColor->blue);
        $hex = strtoupper($hex);

        return new self(new CSSHexColor($hex));
    }

    /**
     * @param string $hex
     *
     * @return CSSHexSpeaker
     */
    public static function fromHexCode(string $hex): ColorSpeakerContract
    {
        return new self(new CSSHexColor($hex));
    }

    /**
     * @param int          $hue
     * @param float|string $saturation Either float or "55.5%"
     * @param float|string $lightness  Either float or "55.5%"
     * @return ColorSpeakerContract
     */
    public static function fromHSL(int $hue, $saturation, $lightness): ColorSpeakerContract
    {
        $hexColor = HSLSpeaker::fromHSL($hue, $saturation, $lightness)->toHexCode();

        return new self($hexColor);
    }

    public function __toString(): string
    {
        return $this->color->__toString();
    }

    public function toRGB(): RGBColor
    {
        return $this->convertToRGB();
    }

    public function toHexCode(): CSSHexColor
    {
        return $this->color;
    }

    public function toHSL(): HSLColor
    {
        $rgbColor = $this->convertToRGB();

        return HSLSpeaker::fromRGB($rgbColor->red, $rgbColor->green, $rgbColor->blue)->toHSL();
    }

    /**
     * Taken from https://stackoverflow.com/a/15202130/430062.
     *
     * @return RGBColor
     */
    private function convertToRGB(): RGBColor
    {
        // Now *THIS* is some arcane PHP!
        $hex = (string) $this->color;
        // @todo Please, if you know of a better way to do this, I'd love a PR!
        $hex = strlen($hex) === 4 ? "#$hex[1]$hex[1]$hex[2]$hex[2]$hex[3]$hex[3]" : $hex;

        [$r, $g, $b] = sscanf($hex, '#%2x%2x%2x');

        return new RGBColor([$r, $g, $b]);
    }
}
