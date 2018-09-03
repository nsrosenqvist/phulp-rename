Phulp Rename
============

It's a third-party project that lets you rename files.

## Installation

```bash
composer require nsrosenqvist/phulp-rename
```

## Usage

You can either set the rules by an associative array. This isn't very flexible since
the same rules would apply to all files and you might want to process their current
filename in order to figure out the new name, so you can also pass a function and
return the rule for each file.

```php
<?php

use NSRosenqvist\Phulp\Rename;

$phulp->task('images', function($phulp) {
    // By Array
    $phulp->src(['assets/images/'], '/JPG$/')
        ->pipe(new Rename([
            'prefix' => 'camera-',
            'suffix' => '2018',
            'extension' => 'jpg',
            // Other editable keys:
            // - filename
            // - dirname
        ]))
        ->pipe($phulp->dest('dist/images/'));

    // By function
    $phulp->src(['assets/images/'], '/jpg$/')
        ->pipe(new Rename(function($name) {
            $name['prefix'] = 'image-';
            $name['filename'] = md5($name['filename']);
            return $name;
        }))
        ->pipe($phulp->dest('dist/images/'));
});
```

## License
MIT
