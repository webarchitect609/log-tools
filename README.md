PSR-3 compatible logger tools

How to use:
-----------

1 Install via [composer](https://getcomposer.org/)

`composer require webarchitect609/log-tools`

2 Use `\WebArch\LogTools\Traits\LogExceptionTrait` instead of `\Psr\Log\LoggerAwareTrait` in the class you want to be 
able to log exceptions in more convenient way. Do not forget to implement `\Psr\Log\LoggerAwareInterface`.

3 When an exception or error occures feel free to use `logException()` method to log it nice and easy. 

```php
class Foo implements \Psr\Log\LoggerAwareInterface
{
    use \WebArch\LogTools\Traits\LogExceptionTrait;

    public function __construct()
    {
        $this->setLogger(new \Psr\Log\NullLogger());
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
                \Psr\Log\LogLevel::CRITICAL,
                /**
                 * Additional context(optional).
                 */
                ['var1' => 'ABC']
            );
        }
    }

}
```
