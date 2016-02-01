# Default Autowire

[![Build Status](https://img.shields.io/travis/Symplify/DefaultAutowire.svg?style=flat-square)](https://travis-ci.org/Symplify/DefaultAutowire)
[![Quality Score](https://img.shields.io/scrutinizer/g/Symplify/DefaultAutowire.svg?style=flat-square)](https://scrutinizer-ci.com/g/Symplify/DefaultAutowire)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Symplify/DefaultAutowire.svg?style=flat-square)](https://scrutinizer-ci.com/g/Symplify/DefaultAutowire)
[![Downloads](https://img.shields.io/packagist/dt/symplify/default-autowire.svg?style=flat-square)](https://packagist.org/packages/symplify/default-autowire)
[![Latest stable](https://img.shields.io/packagist/v/symplify/default-autowire.svg?style=flat-square)](https://packagist.org/packages/symplify/default-autowire)

**This bundle turns on autowire for services only when needed - it works for you!**

```yaml
# app/config/config.yml
services:
    some_service:
        class: SomeClass
        # ? autowired: true 
```

Turning on autowire feature can be demanding. You have to turn it manually and think about many different situations.

- Sometimes you have argument that needs to be autowired.
- Sometimes you remove them and autowiring is needed no more.
- Sometimes you create new dependency and the service now needs to be autowired.

No need to think if you need to add autowire or not - this can be easily automated.


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
