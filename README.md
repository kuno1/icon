# icon

An icon communicator for WordPress.
You can include **Icon Block** and **Inline Icon** in your block editor.

## Supported Icons

- Dashicons
- Font Awesome 5

You can add your original icons.

## Core Concept

This library will parse and grab the icon names from your web font CSS.
If you like to choose your iconsets(e.g. for performance), customize your web-font CSS.

## Installation

Use composer.

```bash
composer require kunoich/icon
```

From your theme or plugin's boostrap file, include `autoloader.php`.

```php
require __DIR__ . '/vendor/autoload.php';
```

Then enable icon blocks.

```php
Kunoichi\Icon\Manager::register();
```

## Customize

### Create Your Own

Each font are parsed by a class which inherits `Kunoichi\Icon\Pattern\IconSet`.

You have to parse CSS by your self. For details, see `Kunoichi\Icon\Iconsets\Dashicons` and do what it does.

Before `Manager::register`, just make new instance of your class.

```php
new YourIconSets( 'path/to/your/icon.css' );
Kunoichi\Icon\Manager::register();
```

For Font Awesome 5 Pro, see `Kunoichi\Icon\Iconsets\FontAwesomeSolid` for your information.
It parses SVG :)

### Remove Defaults

If you don't use some of default icons(e.g. dashicons), pass argument for exclude list.

```php
Kunoichi\Icon\Manager::register( [ 'dashicons' => false ] );
```

Thus, no dashicons will be listed.

## License

GPL 3.0 or later.
Font Awesome is under [Font Awesome Free License](https://fontawesome.com/license/free).