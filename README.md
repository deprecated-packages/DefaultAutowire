# Default Autowire

[![Build Status](https://img.shields.io/travis/Symplify/DefaultAutowire.svg?style=flat-square)](https://travis-ci.org/Symplify/DefaultAutowire)
[![Quality Score](https://img.shields.io/scrutinizer/g/Symplify/DefaultAutowire.svg?style=flat-square)](https://scrutinizer-ci.com/g/Symplify/DefaultAutowire)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Symplify/DefaultAutowire.svg?style=flat-square)](https://scrutinizer-ci.com/g/Symplify/DefaultAutowire)
[![Downloads](https://img.shields.io/packagist/dt/symplify/default-autowire.svg?style=flat-square)](https://packagist.org/packages/symplify/default-autowire)
[![Latest stable](https://img.shields.io/packagist/v/symplify/default-autowire.svg?style=flat-square)](https://packagist.org/packages/symplify/default-autowire)

**This bundle turns on autowire for you!**

It turn this:

```yaml
# app/config/config.yml
services:
    price_calculator:
        class: PriceCalculator
        autowired: true

    product_repository:
        class: ProductRepository
        autowired: true

    user_factory:
        class: UserFactory
        autowired: true
```

Into this:

```yaml
# app/config/config.yml
services:
    price_calculator:
        class: PriceCalculator

    product_repository:
        class: ProductRepository

    user_factory:
        class: UserFactory
```

## Install

```bash
$ composer require symplify/default-autowire
```

Add bundle to `AppKernel.php`:

```php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symplify\DefaultAutowire\SymplifyDefaultAutowireBundle(),
            // ...
        ];
    }
}
```

And that's it!


# Testing

```bash
$ vendor/bin/phpunit
```


# Contributing

Rules are simple:

- new feature needs tests
- all tests must pass
- 1 feature per PR

I'd be happy to merge your feature then.
