<?php declare(strict_types=1);

namespace PHPExperts\ColorSpeaker;

use PHPExperts\ColorSpeaker\DTOs\CSSHexColor;
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
     * @return self
     */
    public static function fromRGB(int $red, int $green, int $blue): ColorSpeakerContract
    {
        $rgbSpeaker = RGBSpeaker::fromRGB($red, $green, $blue);

        return new self($rgbSpeaker);
    }

    /**
     * @param string $hexColor
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
     * @internal
     */
    public function getTranslator(): ColorSpeakerContract
    {
        return $this->colorSpeaker;
    }
}
