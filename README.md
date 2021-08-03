# Enum cast for Eloquent

[![Latest Version on Packagist](https://img.shields.io/packagist/v/orkhanahmadov/eloquent-enum-cast.svg?style=flat-square)](https://packagist.org/packages/orkhanahmadov/eloquent-enum-cast)
[![Total Downloads](https://img.shields.io/packagist/dt/orkhanahmadov/eloquent-enum-cast.svg?style=flat-square)](https://packagist.org/packages/orkhanahmadov/eloquent-enum-cast)
![GitHub Actions](https://github.com/orkhanahmadov/eloquent-enum-cast/actions/workflows/main.yml/badge.svg)

Simple Enum cast for Eloquent ORM using [myclabs/php-enum](https://github.com/myclabs/php-enum).

## TODO

- Tests

## Installation

You can install the package via composer:

```bash
composer require orkhanahmadov/eloquent-enum-cast
```

## Usage

Let's say you have following Enum class using [myclabs/php-enum](https://github.com/myclabs/php-enum).

```php
namespace App\Enums;

use MyCLabs\Enum\Enum;

class Role extends Enum
{
    private const ADMIN = 1;
    private const USER = 2;
}
```

To make it Eloquent castable, instead of `MyCLabs\Enum\Enum`, extend from `Orkhanahmadov\EloquentEnumCast\EnumCast`

```php
namespace App\Enums;

use Orkhanahmadov\EloquentEnumCast\EnumCast;

class Role extends EnumCast
{
    private const ADMIN = 1;
    private const USER = 2;
}
```

> Note: `Orkhanahmadov\EloquentEnumCast\EnumCast` extends `MyCLabs\Enum\Enum`, you can still use any other methods and properties that it has.

Last thing, in your Eloquent model cast attribute using your Enum class

```php
use App\Enums\Role;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $casts = [
        'role' => Role::class,
    ];
}
```

Now Eloquent will cast raw database values to your `App\Enums\Role` enum whenever you retrieve User model from database.

Likewise, whenever you save User model with `App\Enums\Role` enum as `role` attribute Eloquent will automatically save enum's underlying value into database.

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email hi@orkhan.dev instead of using the issue tracker.

## Credits

-   [Orkhan Ahmadov](https://github.com/orkhanahmadov)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.