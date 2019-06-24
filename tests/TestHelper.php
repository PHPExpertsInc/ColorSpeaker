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
    public static function fetchGoodColorSets(): array
    {
        return [
            /** Hex           RGB                 HSL          */
            ['#00BFFF', [  0, 191, 255], [195,    100,    50]],
            ['#00BFFF', [  0, 191, 255], [195, '100%', '50%']],
            ['#00BFFF', [  0, 191, 255], [195,    100,    50]],
            ['#0A94C2', [ 10, 148, 194], [195,     90,    40]],
            ['#0A94C2', [ 10, 148, 194], [195,  '90%', '40%']],
            ['#0A94C2', [ 10, 148, 194], [195,     90,    40]],
            ['#0F8A42', [ 15, 138,  66], [145,     80,    30]],
            ['#0F8A42', [ 15, 138,  66], [145,  '80%', '30%']],
            ['#0F8A42', [ 15, 138,  66], [145,     80,    30]],
            ['#534646', [ 83,  70,  70], [  0,      8,    30]],
            ['#534646', [ 83,  70,  70], [  0,   '8%', '30%']],
            ['#534646', [ 83,  70,  70], [  0,      8,    30]],
            ['#5346b4', [ 83,  70, 180], [247,     44,    49]],
            ['#5346b4', [ 83,  70, 180], [247,  '44%', '49%']],
            ['#5346b4', [ 83,  70, 180], [247,     44,    49]],
            ['#999999', [153, 153, 153], [  0,      0,    60]],
            ['#AD0101', [173,   1,   1], [  0,     99,    34]],
            ['#AD0101', [173,   1,   1], [  0,  '99%', '34%']],
            ['#AD0101', [173,   1,   1], [  0,     99,    34]],
            ['#123354', [ 18,  51,  84], [210,     65,    20]],
            ['#813737', [129,  55,  55], [  0,     40,    36]],
            ['#375081', [ 55,  80, 129], [220,     40,    36]],
            ['#3A8137', [ 58, 129,  55], [118,     40,    36]],
            ['#09EF01', [  9, 239,   1], [118,     99,    47]],
            ['#000099', [  0,   0, 153], [240,    100,    30]],

            ['#000000', [  0,   0,   0], [ rand(0, 359),  rand(0, 100),   0]],
            ['#FFFFFF', [255, 255, 255], [ rand(0, 359),  rand(0, 100), 100]],
        ];
    }
}
