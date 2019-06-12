# ColorSpeaker

[![Travis CI](https://travis-ci.org/phpexpertsinc/ColorSpeaker.svg?branch=master)](https://travis-ci.org/phpexpertsinc/ColorSpeaker)[![Maintainability](https://api.codeclimate.com/v1/badges/503cba0c53eb262c947a/maintainability)](https://codeclimate.com/github/phpexpertsinc/SimpleDTO/maintainability)
[![Maintainability](https://api.codeclimate.com/v1/badges/1dff9e08f54516c41e4d/maintainability)](https://codeclimate.com/github/phpexpertsinc/ColorSpeaker/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/1dff9e08f54516c41e4d/test_coverage)](https://codeclimate.com/github/phpexpertsinc/ColorSpeaker/test_coverage)

ColorSpeaker is a PHP Experts, Inc., Project is an easy-to-use converter for different types of color models.

It aims to support all major color models: RGB, CSS hex codes, HSL and HSV.

## Installation

Via Composer

```bash
composer require phpexperts/color-speaker
```

## Usage

Initialize it with 3 standard RGB integers:
```php
$rgb = new RGBSpeaker(123, 111, 55);
```
It can easily be used as a string for css-compatible output:
```php
$csv = ".box { background-color: $rgb; }";
// .box { background-color: rgb(123, 111,55); }
```
You can also fetch the RGBColor directly:
```php
$rgbColor = $rgb->toRGB();
/*
   SimpleDTO => {
       'red'   => 123,
       'green' => 111,
       'blue'  => 55
   };
*/
```
See the [**SimpleDTO Project**](https://github.com/phpexpertsinc/simple-dto) for more.

You can also export to different color formats:
```php
$hexcode = $rgb->toHex();
// #7B6F37

// You can also make the hex code lowercase:
$hexcode = $rgb->toHex(false);
// #7b6f37
```

# Use cases

PHPExperts\ColorSpeaker\DTOs\RGBColor  
 ✔ Will only accept integers between 0 and 255, inclusive  
 ✔ Will only accept literal integers  
 ✔ Can be constructed with a zero-indexed array  
 ✔ Can be outputted as a CSS string  

PHPExperts\ColorSpeaker\RGBSpeaker  
 ✔ Can be constructed from an RGBColor  
 ✔ Can be constructed from a HexColor  
 ✔ Will only accept integers between 0 and 255, inclusive  
 ✔ Can return an RGBColor  
 ✔ Can return a CSSHexColor  
 ✔ Can be outputted as a CSS string  

PHPExperts\ColorSpeaker\DTOs\CSSHexColor  
 ✔ Can assert if a string is a valid CSS hex color  
 ✔ The hex code must start with a "#" sign  
 ✔ Will only accept three digit and six digit hex codes  
 ✔ Can be outputted as a CSS string  

PHPExperts\ColorSpeaker\CSSHexSpeaker  
 ✔ Can be constructed from an RGB Color  
 ✔ Can be constructed from a HexColor  
 ✔ Can return an RGBColor  
 ✔ Can return a CSSHexColor  
 ✔ Can be outputted as a CSS string  


## Testing

```bash
phpunit --testdox
```

# Contributors

[Theodore R. Smith](https://www.phpexperts.pro/]) <theodore@phpexperts.pro>  
GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690  
CEO: PHP Experts, Inc.

## License

MIT license. Please see the [license file](LICENSE) for more information.
