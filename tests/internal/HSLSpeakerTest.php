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

namespace PHPExperts\ColorSpeaker\Tests\internal;

use PHPExperts\ColorSpeaker\DTOs\CSSHexColor;
use PHPExperts\ColorSpeaker\DTOs\HSLColor;
use PHPExperts\ColorSpeaker\internal\HSLSpeaker;
use PHPExperts\ColorSpeaker\Tests\TestHelper;
use PHPExperts\DataTypeValidator\InvalidDataTypeException;
use PHPExperts\ColorSpeaker\DTOs\RGBColor;
use PHPUnit\Framework\TestCase;

/** @testdox PHPExperts\ColorSpeaker\HSLSpeaker */
class HSLSpeakerTest extends TestCase
{
    /** @testdox Can be constructed from an RGBColor */
    public function testCanBeConstructedFromAnRGBColor()
    {
        $colorSets = TestHelper::fetchGoodColorSets();

        foreach ($colorSets as [$cssInfo, $rgbInfo, $hslInfo]) {
            // Test for 0, 0s.
            if ($hslInfo[2] === 0 || $hslInfo[2] === 100) {
                $hslInfo[0] = $hslInfo[1] = 0;
            }

            $hslColor = new HSLColor($hslInfo);
            $expected = new HSLSpeaker($hslColor);
            $actual = HSLSpeaker::fromRGB($rgbInfo[0], $rgbInfo[1], $rgbInfo[2]);

            self::assertEquals($expected, $actual);
        }
    }

    /** @testdox Can be constructed from a HexColor */
    public function testCanBeConstructedFromAHexColor()
    {
        $hslColor = new HSLColor([210, 65, 20]);
        $expected = new HSLSpeaker($hslColor);
        $actual = HSLSpeaker::fromHexCode('#123456');

        self::assertEquals($expected, $actual);
    }

    /** @testdox Can be constructed from an HSLColor */
    public function testCanBeConstructedFromAnHSLColor()
    {
        $hslColor = new HSLColor([210, 65, 20]);
        $expected = new HSLSpeaker($hslColor);
        $actual = HSLSpeaker::fromHSL(210, '65%', '20%');

        self::assertEquals($expected, $actual);
    }

    /** @testdox Will only accept a valid HSL geometry of percentages or percent-integers */
    public function testWillOnlyAcceptHuInclusive()
    {
        $hsl = new HSLSpeaker(new HSLColor([359, 0, 99]));
        self::assertInstanceOf(HSLSpeaker::class, $hsl);

        try {
            new HSLSpeaker(new HSLColor([-1, 0, 101]));
            $this->fail('Created an invalid DTO.');
        } catch (InvalidDataTypeException $e) {
            $expected = [
                'hue'        => 'Must be between 0 and 359, not -1',
                'lightness'  => 'Must be between 0 and 100, not 101',
            ];

            self::assertSame('Invalid HSL geometry.', $e->getMessage());
            self::assertSame($expected, $e->getReasons());
        }
    }

    /** @testdox Can return an RGBColor */
    public function testCanReturnAnRGBColor()
    {
        $colorSets = TestHelper::fetchGoodColorSets();

        foreach ($colorSets as [$cssInfo, $rgbInfo, $hslInfo]) {
            $expectedDTO = new RGBColor(['red' => $rgbInfo[0], 'green' => $rgbInfo[1], 'blue' => $rgbInfo[2]]);
            $hslColor = new HSLColor(['hue' => $hslInfo[0], 'saturation' => $hslInfo[1], 'lightness' => $hslInfo[2]]);
            $hsl = new HSLSpeaker($hslColor);

            self::assertEquals($expectedDTO, $hsl->toRGB());
        }
    }

    /** @testdox Can return a CSSHexColor */
    public function testCanReturnACSSHexColor()
    {
        $colorSets = TestHelper::fetchGoodColorSets();

        foreach ($colorSets as [$expectedHex, $rgbInfo, $hslInfo]) {
            $expectedDTO = new CSSHexColor($expectedHex);
            $hslColor = new HSLColor(['hue' => $hslInfo[0], 'saturation' => $hslInfo[1], 'lightness' => $hslInfo[2]]);
            $hsl = new HSLSpeaker($hslColor);

            self::assertEquals($expectedDTO, $hsl->toHexCode());
            self::assertEquals(strtoupper($expectedHex), (string) $hsl->toHexCode());
        }
    }

    /** @testdox Can return an HSLColor */
    public function testCanReturnAnHSLColor()
    {
        $colorSets = TestHelper::fetchGoodColorSets();

        foreach ($colorSets as [$cssInfo, $rgbInfo, $hslInfo]) {
            // Test for 0, 0s.
            if ($hslInfo[2] === 0 || $hslInfo[2] === 100) {
                $hslInfo[0] = $hslInfo[1] = 0;
            }

            $expectedDTO = new HSLColor(['hue' => $hslInfo[0], 'saturation' => $hslInfo[1], 'lightness' => $hslInfo[2]]);
            $hslColor = new HSLColor(['hue' => $hslInfo[0], 'saturation' => $hslInfo[1], 'lightness' => $hslInfo[2]]);
            $hsl = new HSLSpeaker($hslColor);

            self::assertEquals($expectedDTO, $hsl->toHSL());
        }
    }

    /** @testdox Can be outputted as a CSS string */
    public function testCanBeOutputtedAsACSSString()
    {
        // rgb(127, 127, 127)
        $expected = 'hsl(0, 0%, 50%)';
        $hsl = new HSLSpeaker(new HSLColor([0, 0, 50]));
        self::assertEquals($expected, (string) $hsl);
    }
}
