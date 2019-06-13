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
final class RGBSpeaker implements ColorSpeakerContract
{
    /** @var RGBColor */
    private $color;

    public function __construct(RGBColor $rgbColor)
    {
        $this->color = $rgbColor;
    }

    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     *
     * @return RGBSpeaker
     */
    public static function fromRGB(int $red, int $green, int $blue): ColorSpeakerContract
    {
        $rgbColor = new RGBColor(['red' => $red, 'green' => $green, 'blue' => $blue]);

        return new self($rgbColor);
    }

    /**
     * @param string $hex
     *
     * @return RGBSpeaker
     */
    public static function fromHexCode(string $hex): ColorSpeakerContract
    {
        $rgbColor = CSSHexSpeaker::fromHexCode($hex)->toRGB();

        return new self($rgbColor);
    }

    public static function fromHSL(int $hue, $saturation, $lightness): ColorSpeakerContract
    {
        $rgbColor = HSLSpeaker::fromHSL($hue, $saturation, $lightness)->toRGB();

        return new self($rgbColor);
    }

    public function __toString(): string
    {
        return $this->color->__toString();
    }

    public function toRGB(): RGBColor
    {
        return $this->color;
    }

    public function toHexCode(): CSSHexColor
    {
        $rgbColor = $this->color;

        return CSSHexSpeaker::fromRGB($rgbColor->red, $rgbColor->green, $rgbColor->blue)->toHexCode();
    }

    public function toHSL(): HSLColor
    {
        $rgbColor = $this->toRGB();

        return HSLSpeaker::fromRGB($rgbColor->red, $rgbColor->green, $rgbColor->blue)->toHSL();
    }
}
