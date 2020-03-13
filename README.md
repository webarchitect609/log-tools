Log Tools
=========
[![Travis Build Status](https://travis-ci.org/webarchitect609/log-tools.svg?branch=master)](https://travis-ci.org/webarchitect609/log-tools)
[![Latest version](https://img.shields.io/github/v/tag/webarchitect609/log-tools?sort=semver)](https://github.com/webarchitect609/log-tools/releases)
[![Downloads](https://img.shields.io/packagist/dt/webarchitect609/log-tools)](https://packagist.org/packages/webarchitect609/log-tools)
[![PHP version](https://img.shields.io/packagist/php-v/webarchitect609/log-tools)](https://www.php.net/supported-versions.php)
[![License](https://img.shields.io/github/license/webarchitect609/log-tools)](LICENSE.md)
[![More stuff from me](https://img.shields.io/badge/packagist-webarchitect609-blueviolet)](https://packagist.org/packages/webarchitect609/)

PSR-3 compatible logger tools

Features
--------
- Log any exception properly: with the stack trace, with all previous exceptions, etc
- Daily logger: setup directory with log files with 'Y_m_d' timestamp as a filename

Installation
------------
`composer require webarchitect609/log-tools`

Usage
-----
### LogExceptionTrait

Use `\WebArch\LogTools\Traits\LogExceptionTrait` instead of `\Psr\Log\LoggerAwareTrait` in the class you want to be 
able to log exceptions in more convenient way. Do not forget to implement `\Psr\Log\LoggerAwareInterface`.

When an exception or error occurs feel free to use `logException()` method to log it nice and easy. 

```php
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;
use WebArch\LogTools\Traits\LogExceptionTrait;

class FooService implements LoggerAwareInterface
{
    use LogExceptionTrait;

    public function __construct()
    {
        $this->setLogger(new NullLogger());
    }

    public function bar()
    {
        try {

            throw new LogicException('Exception occures in ' . __METHOD__);

        } catch (Throwable $exception) {

            /**
             * Exception will be logged with type, message, code and stack trace.
             */
            $this->logException(
                $exception,
                /**
                 * Set custom log message level(optional).
                 */
                LogLevel::CRITICAL,
                /**
                 * Additional context(optional).
                 */
                ['var1' => 'ABC']
            );
        }
    }

}
```

### MonologLoggerFactory

Use `\WebArch\LogTools\Factory\MonologLoggerFactory` to simplify creating logs on daily basis. 

```php
use WebArch\LogTools\Enum\SystemStream;
use WebArch\LogTools\Factory\MonologLoggerFactory;

$debug = false;
$loggerFactory = new MonologLoggerFactory('/tmp/log/www', $debug);
$logger = $loggerFactory->createDailyLogger('bar', 'foo', SystemStream::STDERR);

/**
 * Creates `/tmp/log/www/foo/2019_02_15.log`
 * and outputs `[2019-02-15 12:42:59] bar.INFO: Hello, world! [] []` there
 * and to the STDERR. 
 */
$logger->info(
    'Hello, world!'
);
```


Known Issues
------------
None so far.

Licence & Author Information
----------------------------
[BSD-3-Clause](LICENSE.md)
