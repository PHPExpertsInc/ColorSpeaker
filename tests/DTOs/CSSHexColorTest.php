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

use PHPExperts\ColorSpeaker\DTOs\CSSHexColor;
use PHPExperts\DataTypeValidator\InvalidDataTypeException;
use PHPExperts\SimpleDTO\SimpleDTO;
use PHPUnit\Framework\TestCase;

/** @testdox PHPExperts\ColorSpeaker\DTOs\CSSHexColor */
class CSSHexColorTest extends TestCase
{
    /** @testdox Can assert if a string is a valid CSS hex color */
    public function testAssertIsValidCSSHexColor()
    {
        $good = [
            '#ccc',
            '#cccccc',
            '#CCC',
            '#CCCCCC',
            '#1F4aAc',
            '#AbC',
        ];

        $bad = [
            '#',
            '#1',
            '#12',
            '#1234',
            '#12345',
            '#1234567',
            '#gaa',
            '#-555',
            '#hhhhhhh',
        ];

        foreach ($good as $css) {
            self::assertTrue(CSSHexColor::assertIsValidCSSHexColor($css));
        }

        foreach ($bad as $css) {
            try {
                CSSHexColor::assertIsValidCSSHexColor($css);
            } catch (InvalidDataTypeException $e) {
                self::assertEquals("'$css' is not a valid CSS hexadecimal color code.", $e->getMessage());
            }
        }
    }

    /** @testdox The hex code must start with a "#" sign */
    public function testTheHexCodeMustStartWithAPoundSign()
    {
        $good = [
            '#333',
            '#333333',
        ];

        $bad = [
            '',
            '111',
            '444444',
        ];

        foreach ($good as $hex) {
            $hexColor = new CSSHexColor($hex);
            self::assertInstanceOf(CSSHexColor::class, $hexColor);
            self::assertInstanceOf(SimpleDTO::class, $hexColor);
            self::assertEquals($hex, $hexColor);
        }

        foreach ($bad as $hex) {
            try {
                new CSSHexColor($hex);
                $this->fail('Created an invalid hex color');
            } catch (InvalidDataTypeException $e) {
                $expected = 'Hex colors must begin with "#".';
                self::assertSame($expected, $e->getMessage());
            }
        }
    }

    public function testWillOnlyAcceptThreeDigitAndSixDigitHexCodes()
    {
        $good = [
            '#333',
            '#333333',
        ];

        $bad = [
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
            $hexColor = new CSSHexColor($hex);
            self::assertInstanceOf(CSSHexColor::class, $hexColor);
            self::assertInstanceOf(SimpleDTO::class, $hexColor);
            self::assertEquals($hex, $hexColor);
        }

        foreach ($bad as $hex) {
            try {
                new CSSHexColor($hex);
                $this->fail('Created an invalid hex color');
            } catch (InvalidDataTypeException $e) {
                $digits = strlen($hex) - 1;
                $expected = $hex !== '#' && ($hex[0] ?? '') !== '#' ?
                    'Hex colors must begin with "#".' :
                    'Hex color codes must be 3 or 6 digits, not ' . $digits . " ($hex)";
                self::assertEquals($expected, $e->getMessage());
            }
        }
    }

    /** @testdox Can be outputted as a CSS string */
    public function testCanBeOutputtedAsACSSString()
    {
        // Long hex code
        $expected = '#FC5DE3';
        $hex = new CSSHexColor('#fc5De3');
        self::assertEquals($expected, (string) $hex);

        // Short hex code
        $expected = '#666';
        $hex = new CSSHexColor('#666');
        self::assertEquals($expected, (string) $hex);
    }
}
