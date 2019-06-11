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

use PHPExperts\DataTypeValidator\InvalidDataTypeException;
use PHPExperts\ColorSpeaker\RGBSpeaker;
use PHPExperts\ColorSpeaker\DTOs\RGBColor;
use PHPUnit\Framework\TestCase;

/** @testdox PHPExperts\ColorSpeaker\RGBSpeaker */
class RGBSpeakerTest extends TestCase
{
    /** @testdox Can be constructed from an RGB Color */
    public function testCanBeConstructedFromAnRGBColor()
    {
        $rgbColor = new RGBColor([0, 0, 255]);
        $expected = new RGBSpeaker(0, 0, 255);
        $actual = RGBSpeaker::fromRGB($rgbColor);

        self::assertEquals($expected, $actual);
    }

    /** @testdox Will only accept integers between 0 and 255, inclusive */
    public function testWillOnlyAcceptIntegersBetween0And255Inclusive()
    {
        $rgb = new RGBSpeaker(0, 0, 255);
        self::assertInstanceOf(RGBSpeaker::class, $rgb);

        try {
            new RGBSpeaker(-1, 5, 256);
            $this->fail('Created an invalid DTO.');
        } catch (InvalidDataTypeException $e) {
            $expected = [
                'red'  => 'Must be greater than or equal to 0',
                'blue' => 'Must be lesser than or equal to 255',
            ];

            self::assertSame('Color values must be between 0 and 255, inclusive.', $e->getMessage());
            self::assertSame($expected, $e->getReasons());
        }
    }

    /** @testdox Can return its RGBDTO */
    public function testCanReturnItsRGBDTO()
    {
        $expectedDTO = new RGBColor(['red' => 1, 'green' => 1, 'blue' => 1]);
        $rgb = new RGBSpeaker(1, 1, 1);
        self::assertEquals($expectedDTO, $rgb->toRGB());
    }

    /** @testdox Can be outputted as a CSS string */
    public function testCanBeOutputtedAsACSSString()
    {
        $expected = 'rgb(127, 127, 127)';
        $rgb = new RGBSpeaker(127, 127, 127);
        self::assertEquals($expected, (string) $rgb);
    }

    public function testCanBeConvertedToHexCode()
    {
        $rgbHexPairs = [
            '#803737' => new RGBColor([128,  55, 55]),
            '#374F80' => new RGBColor([55,  79, 128]),
            '#398037' => new RGBColor([57, 128,  55]),
            '#09EC01' => new RGBColor([9, 236,   1]),
            '#000099' => new RGBColor([0,   0, 153]),
        ];

        foreach ($rgbHexPairs as $expected => $rgbDTO) {
            $rgb = RGBSpeaker::fromRGB($rgbDTO);
            self::assertSame($expected, $rgb->toHex());
        }
    }
}
