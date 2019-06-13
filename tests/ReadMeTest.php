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
use PHPExperts\ColorSpeaker\DTOs\RGBColor;
use PHPUnit\Framework\TestCase;

/** @testdox Tests for all of the README's examples */
class ReadMeTest extends TestCase
{
    public function testExample1()
    {
        $rgbSpeaker = ColorSpeaker::fromRGB(123, 111, 55);

        $expected = '.box { background-color: rgb(123, 111, 55); }';
        $csv = ".box { background-color: $rgbSpeaker; }";

        self::assertEquals($expected, $csv);
    }

    public function testExample2()
    {
        $rgbSpeaker = ColorSpeaker::fromRGB(123, 111, 55);

        $rgbColor = $rgbSpeaker->toRGB();

        // (string) $rgbColor === rgb(123, 111, 55);
        self::assertSame('rgb(123, 111, 55)', (string) $rgbSpeaker);

        $expectedDTO = new RGBColor([123, 111, 55]);
        self::assertEquals($expectedDTO, $rgbColor);
    }

    public function testExample3()
    {
        $rgbSpeaker = ColorSpeaker::fromRGB(123, 111, 55);

        $hexColor = $rgbSpeaker->toHexCode();

        // (string) $hexColor === #7B6F37
        self::assertSame('#7B6F37', (string) $hexColor);

        $expectedDTO = new CSSHexColor('#7B6F37');
        self::assertEquals($expectedDTO, $hexColor);
    }

    public function testExample4()
    {
        $linguist = ColorSpeaker::fromHexCode('#7B6F37');
        $rgbColor = $linguist->toRGB();

        $expected = <<<JSON
{
    "red": 123,
    "green": 111,
    "blue": 55
}
JSON;

        // echo json_encode($rgbColor, JSON_PRETTY_PRINT);
        self::assertEquals($expected, json_encode($rgbColor, JSON_PRETTY_PRINT));
    }
}
