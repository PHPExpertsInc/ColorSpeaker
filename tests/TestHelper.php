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

class TestHelper
{
    public static function fetchGoodColorPairs(): array
    {
        return [
            /** Hex           RGB                 HSL          */
            ['#00BFFF', [  0, 191, 255], [195,    100,    50]],
            ['#00BFFF', [  0, 191, 255], [195, '100%', '50%']],
            ['#00BFFF', [  0, 191, 255], [195,   1.00,  0.50]],
            ['#0A94C2', [ 10, 148, 194], [195,     90,    40]],
            ['#0A94C2', [ 10, 148, 194], [195,  '90%', '40%']],
            ['#0A94C2', [ 10, 148, 194], [195,   0.90,  0.40]],
            ['#0F8A42', [ 15, 138,  66], [145,     80,    30]],
            ['#0F8A42', [ 15, 138,  66], [145,  '80%', '30%']],
            ['#0F8A42', [ 15, 138,  66], [145,   0.80,  0.30]],
            ['#534646', [ 83,  70,  70], [  0,      8,    30]],
            ['#534646', [ 83,  70,  70], [  0,   '8%', '30%']],
            ['#534646', [ 83,  70,  70], [  0,   0.08,  0.30]],
            ['#5346b3', [ 83,  70, 178], [247,     44,    49]],
            ['#5346b3', [ 83,  70, 178], [247,  '44%', '49%']],
            ['#5346b3', [ 83,  70, 178], [247,   0.44,  0.49]],
            ['#AD0101', [173,   1,   1], [360,     99,    34]],
            ['#AD0101', [173,   1,   1], [360,  '99%', '34%']],
            ['#AD0101', [173,   1,   1], [360,   0.99,  0.34]],
            ['#000000', [  0,   0,   0], [ rand(0, 360),  rand(0, 100),   0]],
            ['#FFFFFF', [255, 255, 255], [ rand(0, 360),  rand(0, 100), 100]],
        ];
    }
}
