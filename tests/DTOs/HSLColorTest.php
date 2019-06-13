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

namespace PHPExperts\ColorSpeaker\Tests\DTOs;

use PHPExperts\ColorSpeaker\DTOs\HSLColor;
use PHPExperts\ColorSpeaker\Tests\TestHelper;
use PHPExperts\DataTypeValidator\InvalidDataTypeException;
use PHPExperts\ColorSpeaker\DTOs\RGBColor;
use PHPExperts\SimpleDTO\SimpleDTO;
use PHPUnit\Framework\TestCase;

/** @testdox PHPExperts\ColorSpeaker\DTOs\HSLColor */
class HSLColorTest extends TestCase
{
    public static function fetchBadHSL(): array
    {
        return [
            [361, 100.01, 55], [
                '',
                '',
                '',
            ],
            [0.1, -0.01, -0.01], [
                '',
                '',
                '',
            ],
        ];
    }

    /** @testdox Will only accept a valid HSL geometry as floats, percentages, or percent-integers */
    public function testWillOnlyAcceptAValidHSLGeometry()
    {
        $goodHSLs = array_column(TestHelper::fetchGoodColorPairs(), 2);
        foreach ($goodHSLs as $hsl) {
            try {
                $hslColor = new HSLColor($hsl);
            } catch (InvalidDataTypeException $e) {
                dd([$hsl, $e->getReasons()]);
            }
            self::assertInstanceOf(HSLColor::class, $hslColor);
            self::assertInstanceOf(SimpleDTO::class, $hslColor);
        }

        $badHSLs = self::fetchBadHSL();
        foreach ($badHSLs as $hsl) {
            try {
                new HSLColor($hsl);
                $this->fail('Created an invalid HSLColor');
            } catch (InvalidDataTypeException $e) {
                self::assertEquals('Invalid HSL geometry.', $e->getMessage());
                dd([$hsl, $e->getReasons()]);
            }
        }
    }

    public function testWillOnlyAcceptLiteralIntegers()
    {
        try {
            new RGBColor(['red' => '1', 'green' => 1.1, 'blue' => 0]);
        } catch (InvalidDataTypeException $e) {
            $expected = [
                'red'   => 'red is not a valid int',
                'green' => 'green is not a valid int',
            ];

            self::assertEquals('There were 2 validation errors.', $e->getMessage());
            self::assertEquals($expected, $e->getReasons());
        }
    }

    /** @testdox Can be constructed with a zero-indexed array */
    public function testCanBeConstructedWithAZeroIndexedArray()
    {
        $expected = new RGBColor(['red' => 1, 'green' => 26, 'blue' => 0]);
        $actual = new RGBColor([1, 26, 0]);

        self::assertEquals($expected, $actual);
    }

    /** @testdox Can be outputted as a CSS string */
    public function testCanBeOutputtedAsACSSString()
    {
        $expected = 'rgb(127, 127, 127)';
        $rgb = new RGBColor([127, 127, 127]);
        self::assertEquals($expected, (string) $rgb);
    }
}
