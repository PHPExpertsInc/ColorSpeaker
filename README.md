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
$rgbSpeaker = ColorSpeaker::fromRGB(123, 111, 55);
$hexSpeaker = ColorSpeaker::fromHexCode('#7B6F37');
```
It can easily be used as a string for css-compatible output:
```php
$csv = ".box { background-color: $rgbSpeaker; }";
// .box { background-color: rgb(123, 111, 55); }
$csvHex = ".box { background-color: $hexSpeaker; }";
// .box { background-color: #7B6F37; }
```
You can also fetch the RGBColor and the HexColor directly:
```php
$rgbColor = $rgbSpeaker->toRGB();
/*
   (string) $rgbColor === rgb(123, 111, 55);

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
$hexColor = $rgbSpeaker->toHexCode();
/**
    (string) $hexColor === #7B6F37

    SimpleDTO => {
        'hex' => '#7B6F37
    }
**/
```

All colors serializable and easily converted to JSON objects:

```php
$linguist = ColorSpeaker::fromHexCode('#7B6F37');
$rgbColor = $linguist->toRGB();
echo json_encode($rgbColor, JSON_PRETTY_PRINT);
/**
{
    "red": 123,
    "green": 111,
    "blue": 55
}
**/
```

# Use cases

PHPExperts\ColorSpeaker\ColorSpeaker  
 ✔ Can be constructed from an RGBColor  
 ✔ Can be constructed from a HexColor  
 ✔ From RGB: Will only accept integers between 0 and 255, inclusive  
 ✔ From CSS Hex: Will only accept a valid 3 or 6 digit Hex color string, 
   starting with a "#" sign  
 ✔ Can return an RGBColor  
 ✔ Can return a CSSHexColor  
 ✔ Can be outputted as a CSS string  

PHPExperts\ColorSpeaker\DTOs\RGBColor  
 ✔ Will only accept integers between 0 and 255, inclusive  
 ✔ Will only accept literal integers  
 ✔ Can be constructed with a zero-indexed array  
 ✔ Can be outputted as a CSS string  

PHPExperts\ColorSpeaker\DTOs\CSSHexColor  
 ✔ Can assert if a string is a valid CSS hex color  
 ✔ The hex code must start with a "#" sign  
 ✔ Will only accept three digit and six digit hex codes  
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
