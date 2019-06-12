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
     * @return RGBSpeaker
     */
    public static function fromRGB(int $red, int $green, int $blue): ColorSpeakerContract
    {
        $rgbColor = new RGBColor(['red' => $red, 'blue' => $blue, 'green' => $green]);

        return new self($rgbColor);
    }

    /**
     * @param string $hex
     * @return RGBSpeaker
     */
    public static function fromHexCode(string $hex): ColorSpeakerContract
    {
        $rgbColor = HexSpeaker::fromHexCode($hex)->toRGB();

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

    public function toHexCode(): string
    {
        $rgbColor = $this->color;
        $hex = sprintf('#%02x%02x%02x', $rgbColor->red, $rgbColor->green, $rgbColor->blue);
        $hex = strtoupper($hex);

        return $hex;
    }
}
