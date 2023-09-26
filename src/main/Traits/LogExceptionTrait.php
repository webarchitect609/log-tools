<?php

namespace WebArch\LogTools\Traits;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\LogLevel;
use Throwable;

trait LogExceptionTrait
{
    use LoggerAwareTrait;

    /**
     * @var bool If true, all the exception chain will be logged.
     */
    private bool $chaining = true;

    /**
     * Logs any exception properly: with the stack trace, with all previous exceptions, with exception chaining(if
     * enabled, of course), etc.
     *
     * @param Throwable $exception Any error to be logged.
     * @param string $logLevel Log message level.
     * @param array $context Additional log message context. By default, the stack trace is in the key 'trace' and
     *     if previous exception exists it is in the 'previous' key.
     *
     * @return void
     */
    protected function logException(
        Throwable $exception,
        string $logLevel = LogLevel::ERROR,
        array $context = []
    ): void {
        $this->logger->log(
            $logLevel,
            $this->getExceptionAsString($exception),
            array_merge(
                $context,
                $this->getContext($exception)
            )
        );
        if ($this->isChaining() && $exception->getPrevious() instanceof Throwable) {
            $this->logException(
                $exception->getPrevious(),
                $logLevel,
                $context
            );
        }
    }

    /**
     * @param Throwable $exception
     *
     * @return array
     */
    protected function getContext(Throwable $exception): array
    {
        $context = ['trace' => explode(PHP_EOL, $exception->getTraceAsString())];
        if ($exception->getPrevious() instanceof Throwable) {
            $context['previous'] = $this->getExceptionAsString($exception->getPrevious());
        }

        return $context;
    }

    /**
     * @param Throwable $exception
     *
     * @return string
     */
    protected function getExceptionAsString(Throwable $exception): string
    {
        return sprintf(
            "[%s] %s (%s) in %s:%d",
            get_class($exception),
            $exception->getMessage(),
            $exception->getCode(),
            $exception->getFile(),
            $exception->getLine()
        );
    }

    /**
     * @return bool
     */
    protected function isChaining(): bool
    {
        return $this->chaining;
    }

    /**
     * Enables or disables exception chaining support.
     *
     * @param bool $chaining
     *
     * @return $this
     * @noinspection PhpMissingReturnTypeInspection
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    protected function setChaining(bool $chaining)
    {
        $this->chaining = $chaining;

        return $this;
    }

}
