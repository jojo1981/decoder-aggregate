Decoder aggregate library
=====================

Author: Joost Nijhuis <[jnijhuis81@gmail.com](mailto:jnijhuis81@gmail.com)>

This library will provide a decoder/encoder provider including some default encoders/decoders for `json` and `yaml`.

## Installation

### Library

```bash
git clone https://github.com/jojo1981/decoder-aggregate.git
```

### Composer

[Install PHP Composer](https://getcomposer.org/doc/00-intro.md)

```bash
composer require jojo1981/decoder-aggregate
```

## Basic usage

```php
<?php

use Jojo1981\DecoderAggregate\Factory\EncoderDecoderProviderFactory;

require 'vendor/autoload.php';

$encoderDecoderProviderFactory = new EncoderDecoderProviderFactory();
$encoderDecoderProviderFactory->addDefaultEncoders();
$encoderDecoderProviderFactory->addDefaultENcoders();

$encoderDecoderProvider = $encoderDecoderProviderFactory->getEncoderDecoderProvider();
$jsonResult = $encoderDecoderProvider->getDecoder('json')->decode(<<<JSON
{
  "glossary": {
    "title": "example glossary",
    "GlossDiv": {
      "title": "S",
      "GlossList": {
        "GlossEntry": {
          "ID": "SGML",
          "SortAs": "SGML",
          "GlossTerm": "Standard Generalized Markup Language",
          "Acronym": "SGML",
          "Abbrev": "ISO 8879:1986",
          "GlossDef": {
            "para": "A meta-markup language, used to create markup languages such as DocBook.",
            "GlossSeeAlso": [
              "GML",
              "XML"
            ]
          },
          "GlossSee": "markup"
        }
      }
    }
  }
}
JSON
);

$yamlResult = $encoderDecoderProvider->getDecoder('yaml')->decode(<<<YAML
glossary:
  title: example glossary
  GlossDiv:
    title: S
    GlossList:
      GlossEntry:
        ID: SGML
        SortAs: SGML
        GlossTerm: Standard Generalized Markup Language
        Acronym: SGML
        Abbrev: ISO 8879:1986
        GlossDef:
          para: A meta-markup language, used to create markup languages such as DocBook.
          GlossSeeAlso:
            - GML
            - XML
        GlossSee: markup
YAML
);

```
