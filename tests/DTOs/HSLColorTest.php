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
use PHPExperts\SimpleDTO\SimpleDTO;
use PHPUnit\Framework\TestCase;

/** @testdox PHPExperts\ColorSpeaker\DTOs\HSLColor */
class HSLColorTest extends TestCase
{
    public static function fetchBadHSL(): array
    {
        return [
            [[361, 101, 55], [
                'hue'        => 'Must be between 0 and 359, not 361',
                'saturation' => 'Must be between 0 and 100, not 101',
            ]],
            [[-5, 110, 110], [
                'hue'        => 'Must be between 0 and 359, not -5',
                'saturation' => 'Must be between 0 and 100, not 110',
                'lightness'  => 'Must be between 0 and 100, not 110',
            ]],
            [[-2, -1, -1], [
                'hue'        => 'Must be between 0 and 359, not -2',
                'saturation' => 'Must be between 0 and 100, not -1',
                'lightness'  => 'Must be between 0 and 100, not -1',
            ]],
        ];
    }

    /** @testdox Will only accept a valid HSL geometry of percentages or percent-integers */
    public function testWillOnlyAcceptAValidHSLGeometry()
    {
        $goodHSLs = array_column(TestHelper::fetchGoodColorSets(), 2);
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

        /**
         * @var float[] $hsl
         * @var string[] $expected
         */
        foreach ($badHSLs as [$hsl, $expected]) {
            try {
                new HSLColor($hsl);
                $this->fail('Created an invalid HSLColor');
            } catch (InvalidDataTypeException $e) {
                self::assertEquals('Invalid HSL geometry.', $e->getMessage());

                if ($e->getReasons() !== $expected) {
                    dd([$hsl, $e->getReasons()]);
                }

                self::assertEquals($expected, $e->getReasons());
            }
        }
    }

    /** @testdox Can be constructed with a zero-indexed array */
    public function testCanBeConstructedWithAZeroIndexedArray()
    {
        $expected = new HSLColor(['hue' => 1, 'saturation' => 26, 'lightness' => 0]);
        $actual = new HSLColor([1, 26, 0]);

        self::assertEquals($expected, $actual);
    }

    public function testCanBeConstructedWithIntegers()
    {
        $hslColor = new HSLColor(['hue' => 1, 'saturation' => 26, 'lightness' => 15]);

        self::assertInstanceOf(HSLColor::class, $hslColor);
        self::assertInstanceOf(SimpleDTO::class, $hslColor);
    }

    /** @testdox Can be outputted as a CSS string */
    public function testCanBeOutputtedAsACSSString()
    {
        $expected = 'hsl(127, 11%, 33%)';
        $hsl = new HSLColor([127, 11, 33]);
        self::assertEquals($expected, (string) $hsl);
    }
}
