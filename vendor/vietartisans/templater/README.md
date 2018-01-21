# Templater
[![Build Status](https://travis-ci.org/vietartisans/templater.svg?branch=master)](https://travis-ci.org/vietartisans/templater)

Here is our module to handle templating stuff: Everything related to HTML, CSS, JS. No PHP Logic

| Name    | Description     |
|:---      |:---        |
| Project | VA Core   |
| Module  | Templater |
| Version | 1.1       |
| Author  | @sondoha, @buiquangduc  |

## Installation

```
composer require vietartisans/templater
```

## Usage

**your-file.php**

```php
require_once __DIR__ . '/vendor/autoload.php'; // Import composer autoload engine if you did not do it.

$templater = new VA\Templater(__DIR__.'/templates/'); // Path to your template folder, use twig as default

$templater = new VA\Templater(__DIR__.'/templates/', 'blade'); // Use blade as default engine/adapter

$templater->render('/path/to/template-file.php', [
  'param_1'  => 'value_1',
  ...
  'param_n'  => 'value_n'
]);
```

**/path/to/template-file.php**

```twig
/**
 * @var $param_1 string
 * ...
 * @var $param_n array
 */

<p>Hello, this is my twig template. Here is value of variable $param_1: {{ param_1 }}.</p>
```

We are currently using 2 template engines for our module: Twig and Blade

### Twig

We used Twig template engine for the example above. This engine is powerful and flexible, but it does not support raw PHP.

See more about Twig format here: [Twig Template Engine](http://twig.sensiolabs.org/documentation)

`$param_1` to `$param_n` are variables that you will use in your template file, feel free to name it your way.

### Blade

Blade template engine was developed by Laravel, a popular PHP framework. Its structure looks similar to Twig but it SUPPORTED raw PHP.

See more how to use it here [Blade Templates](https://laravel.com/docs/5.1/blade)

## Wordpress Theme Override
If you run this library in Wordpress, you should use theme file override features.

```
$template->setWordPressThemeSupport('your-app-name');
```
Then you can write template file in theme folder like this `wp-content/themes/{theme-name}/templates/{your-app-name}/your-template.php`

## Changelog

### 1.1

* Update source folder structure
* Integrate Blade

### 1.0

* Integrate twig                
* Setup composer/packagist      
* PSR-4 code standard           
* Fall back folder              
* Travis CI                     
