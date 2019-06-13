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

use PHPExperts\ColorSpeaker\DTOs\CSSHexColor;
use PHPExperts\ColorSpeaker\DTOs\HSLColor;
use PHPExperts\ColorSpeaker\DTOs\RGBColor;
use PHPExperts\ColorSpeaker\internal\ColorSpeakerContract;
use PHPExperts\ColorSpeaker\internal\CSSHexSpeaker;
use PHPExperts\ColorSpeaker\internal\RGBSpeaker;

class ColorSpeaker implements ColorSpeakerContract
{
    /** @var ColorSpeakerContract */
    protected $colorSpeaker;

    protected function __construct(ColorSpeakerContract $colorSpeaker)
    {
        $this->colorSpeaker = $colorSpeaker;
    }

    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     *
     * @return self
     */
    public static function fromRGB(int $red, int $green, int $blue): ColorSpeakerContract
    {
        $rgbSpeaker = RGBSpeaker::fromRGB($red, $green, $blue);

        return new self($rgbSpeaker);
    }

    /**
     * @param string $hexColor
     *
     * @return self
     */
    public static function fromHexCode(string $hexColor): ColorSpeakerContract
    {
        $hexSpeaker = CSSHexSpeaker::fromHexCode($hexColor);

        return new self($hexSpeaker);
    }

    public function __toString(): string
    {
        return $this->colorSpeaker->__toString();
    }

    public function toRGB(): RGBColor
    {
        return $this->colorSpeaker->toRGB();
    }

    public function toHexCode(): CSSHexColor
    {
        return $this->colorSpeaker->toHexCode();
    }

    /**
     * WARNING: This function is meant for testing and not general usage.
     * If you must, please be careful.
     *
     * @return ColorSpeakerContract
     *
     * @internal
     */
    public function getTranslator(): ColorSpeakerContract
    {
        return $this->colorSpeaker;
    }

    public static function fromHSL(int $hue, $saturation, $lightness): ColorSpeakerContract
    {
        // TODO: Implement fromHSL() method.
    }

    public function toHSL(): HSLColor
    {
        // TODO: Implement toHSL() method.
    }
}
