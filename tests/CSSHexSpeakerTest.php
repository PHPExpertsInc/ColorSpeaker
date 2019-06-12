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

use PHPExperts\ColorSpeaker\CSSHexSpeaker;
use PHPExperts\ColorSpeaker\DTOs\CSSHexColor;
use PHPExperts\DataTypeValidator\InvalidDataTypeException;
use PHPExperts\ColorSpeaker\RGBSpeaker;
use PHPExperts\ColorSpeaker\DTOs\RGBColor;
use PHPUnit\Framework\TestCase;

/** @testdox PHPExperts\ColorSpeaker\CSSHexSpeaker */
class CSSHexSpeakerTest extends TestCase
{
    /** @testdox Can be constructed from an RGB Color */
    public function testCanBeConstructedFromAnRGBColor()
    {
        $expected = new CSSHexSpeaker(new CSSHexColor('#0000ff'));

        try {
            CSSHexSpeaker::fromRGB(0, 0, 257);
            $this->fail('Accepted an invalid RGB color range.');
        } catch (InvalidDataTypeException $e) {
            self::assertEquals('Color values must be between 0 and 255, inclusive.', $e->getMessage());
        }

        $actual = CSSHexSpeaker::fromRGB(0, 0, 255);

        self::assertEquals($expected, $actual);
    }

    /** @testdox Can be constructed from a HexColor */
    public function testCanBeConstructedFromAHexColor()
    {
        $hexColor = new CSSHexColor('#123456');
        $expected = new CSSHexSpeaker($hexColor);
        $actual = CSSHexSpeaker::fromHexCode('#123456');

        self::assertEquals($expected, $actual);
    }

    /** @testdox Can return am RGBColor */
    public function testCanReturnAnRGBColor()
    {
        $expectedDTO = new RGBColor([18, 52,86]);
        $hex = new CSSHexSpeaker(new CSSHexColor('#123456'));
        self::assertEquals($expectedDTO, $hex->toRGB());

        $rgbHexPairs = [
            '#123456' => new RGBColor([ 18,  52,  86]),
            '#803737' => new RGBColor([128,  55,  55]),
            '#374F80' => new RGBColor([ 55,  79, 128]),
            '#398037' => new RGBColor([ 57, 128,  55]),
            '#09EC01' => new RGBColor([  9, 236,   1]),
            '#000099' => new RGBColor([  0,   0, 153]),
        ];

        foreach ($rgbHexPairs as $hex => $expected) {
            $hexSpeaker = CSSHexSpeaker::fromHexCode($hex);
            self::assertEquals($expected, $hexSpeaker->toRGB());
            self::assertEquals($expected, $hexSpeaker->toRGB());
        }
    }

    /** @testdox Can return a CSSHexColor */
    public function testCanReturnACSSHexColor()
    {
        $expectedDTO = new CSSHexColor('#123456');
        $hex = new CSSHexSpeaker(new CSSHexColor('#123456'));
        self::assertEquals($expectedDTO, $hex->toHexCode());
    }

    /** @testdox Can be outputted as a CSS string */
    public function testCanBeOutputtedAsACSSString()
    {
        $expected = '#123456';
        $hex = new CSSHexSpeaker(new CSSHexColor('#123456'));
        self::assertEquals($expected, (string) $hex);
    }
}
