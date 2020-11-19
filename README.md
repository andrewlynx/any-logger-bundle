Installation
============

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

As this bundle is dome-only and not published on any repositories,
you have to add path to git in composer.json

```json
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:andrewlynx/any-logger-bundle.git"
        }
    ],
    "minimum-stability": "dev"
```

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require <andrewlynx.any_logger>
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require <andrewlynx.any_logger>
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Andrewlynx\Bundle\AnyLoggerBundle::class => ['all' => true],
];
```

### Step 3: Add it as a Service

To allow autowiring for this Bundle add it to Services config:

```php
// config/services.yaml

services:
    // ...
    Andrewlynx\Bundle\AnyLogger\AnyLogger:
        tags:
            - { name: 'andrewlynx.any_logger' }
```

### Step 4: Add bundle routes

The bundle contains some routes, but they aren't in main app namespace,
so they should be added:

```php
// config/routes/annotations.yaml

// ...
app_file:
    resource: '@AnyLoggerBundle/Resources/config/routes.xml'
```