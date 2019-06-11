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
use PHPExperts\ColorSpeaker\DTOs\RGBColor;
use PHPExperts\SimpleDTO\SimpleDTO;
use PHPUnit\Framework\TestCase;

/** @testdox PHPExperts\ColorSpeaker\RGBColor */
class RGBColorTest extends TestCase
{
    /** @testdox Will only accept integers between 0 and 255, inclusive */
    public function testWillOnlyAcceptIntegersBetween0And255Inclusive()
    {
        $expected = ['red' => 0, 'green' => 0, 'blue' => 255];
        $dto = new RGBColor($expected);
        self::assertInstanceOf(RGBColor::class, $dto);
        self::assertInstanceOf(SimpleDTO::class, $dto);
        self::assertSame($expected, $dto->toArray());

        try {
            new RGBColor(['red' => -1, 'green' => 5, 'blue' => 256]);
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
