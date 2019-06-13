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

namespace PHPExperts\ColorSpeaker\Tests;

use PHPExperts\ColorSpeaker\ColorSpeaker;
use PHPExperts\ColorSpeaker\DTOs\CSSHexColor;
use PHPExperts\ColorSpeaker\internal\CSSHexSpeaker;
use PHPExperts\ColorSpeaker\internal\RGBSpeaker;
use PHPExperts\DataTypeValidator\InvalidDataTypeException;
use PHPExperts\ColorSpeaker\DTOs\RGBColor;
use PHPExperts\SimpleDTO\SimpleDTO;
use PHPUnit\Framework\TestCase;

/** @testdox PHPExperts\ColorSpeaker\ColorSpeaker */
class ColorSpeakerTest extends TestCase
{
    /** @testdox Can be constructed from an RGBColor */
    public function testCanBeConstructedFromAnRGBColor()
    {
        $rgbColor = new RGBColor([0, 0, 255]);
        $hexColor = new CSSHexColor('#0000FF');
        $expected = new RGBSpeaker($rgbColor);

        $actual = ColorSpeaker::fromRGB(0, 0, 255);

        self::assertEquals($expected, $actual->getTranslator());
        self::assertEquals($rgbColor, $actual->getTranslator()->toRGB());
        self::assertEquals($hexColor, $actual->getTranslator()->toHexCode());
    }

    /** @testdox Can be constructed from a HexColor */
    public function testCanBeConstructedFromAHexColor()
    {
        $rgbColor = new RGBColor([18, 52, 86]);
        $hexColor = new CSSHexColor('#123456');
        $expected = new CSSHexSpeaker($hexColor);
        $actual = ColorSpeaker::fromHexCode('#123456');

        self::assertEquals($expected, $actual->getTranslator());
        self::assertEquals($rgbColor, $actual->getTranslator()->toRGB());
        self::assertEquals($hexColor, $actual->getTranslator()->toHexCode());
    }

    /** @testdox From RGB: Will only accept integers between 0 and 255, inclusive */
    public function testWillOnlyAcceptIntegersBetween0And255Inclusive()
    {
        try {
            ColorSpeaker::fromRGB(-1, 5, 256);
            $this->fail('Created an invalid DTO.');
        } catch (InvalidDataTypeException $e) {
            $expected = [
                'red'  => 'Must be greater than or equal to 0, not -1',
                'blue' => 'Must be lesser than or equal to 255, not 256',
            ];

            self::assertSame('Color values must be between 0 and 255, inclusive.', $e->getMessage());
            self::assertSame($expected, $e->getReasons());
        }
    }

    /** @testdox From CSS Hex: Will only accept a valid 3 or 6 digit Hex color string, starting with a "#" sign */
    public function testWillOnlyAcceptAValidHexColorString()
    {
        $good = [
            '#333',
            '#333333',
        ];

        $bad = [
            '',
            '#',
            '#1',
            '#12',
            '#1234',
            '123',
            '#12345',
            '123456',
            '#1234567',
        ];

        foreach ($good as $hex) {
            $speaker = ColorSpeaker::fromHexCode($hex);
            self::assertInstanceOf(ColorSpeaker::class, $speaker);
            self::assertInstanceOf(CSSHexSpeaker::class, $speaker->getTranslator());
            self::assertInstanceOf(SimpleDTO::class, $speaker->getTranslator()->toHexCode());
            self::assertInstanceOf(CSSHexColor::class, $speaker->getTranslator()->toHexCode());
            self::assertEquals($hex, $speaker->getTranslator()->toHexCode());
        }

        foreach ($bad as $hex) {
            try {
                ColorSpeaker::fromHexCode($hex);
                $this->fail('Created a ColorSpeaker with an invalid hex color code.');
            } catch (InvalidDataTypeException $e) {
                $digits = strlen($hex) - 1;
                $expected = $hex !== '#' && ($hex[0] ?? '') !== '#' ?
                    'Hex colors must begin with "#".' :
                    'Hex color codes must be 3 or 6 digits, not ' . $digits . " ($hex)";
                self::assertEquals($expected, $e->getMessage());
            }
        }
    }

    /** @testdox Can return an RGBColor */
    public function testCanReturnAnRGBColor()
    {
        $expectedDTO = new RGBColor([1, 1, 1]);
        $actualDTO = ColorSpeaker::fromRGB(1, 1, 1)->toRGB();
        self::assertEquals($expectedDTO, $actualDTO);
    }

    /** @testdox Can return a CSSHexColor */
    public function testCanReturnACSSHexColor()
    {
        $rgbHexPairs = [
            '#123456' => ColorSpeaker::fromRGB( 18,  52,  86),
            '#803737' => ColorSpeaker::fromRGB(128,  55,  55),
            '#374F80' => ColorSpeaker::fromRGB( 55,  79, 128),
            '#398037' => ColorSpeaker::fromRGB( 57, 128,  55),
            '#09EC01' => ColorSpeaker::fromRGB(  9, 236,   1),
            '#000099' => ColorSpeaker::fromRGB(  0,   0, 153),
        ];

        foreach ($rgbHexPairs as $expected => $colorSpeaker) {
            self::assertEquals($expected, $colorSpeaker->toHexCode());

            $expectedHexColor = new CSSHexColor($expected);
            self::assertEquals($expectedHexColor, $colorSpeaker->toHexCode());
        }
    }

    /** @testdox Can be outputted as a CSS string */
    public function testCanBeOutputtedAsACSSString()
    {
        $expected = 'rgb(128, 128, 128)';
        $rgb = ColorSpeaker::fromRGB(128, 128, 128);
        self::assertSame($expected, (string) $rgb);

        // Long hex code
        $expected = '#808080';
        $hex = ColorSpeaker::fromHexCode('#808080');
        self::assertSame($expected, (string) $hex);

        // Short hex code
        $expected = '#808';
        $hex = ColorSpeaker::fromHexCode('#808');
        self::assertSame($expected, (string) $hex);
    }
}
