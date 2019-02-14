<?php

namespace WebArch\LogTools\Traits;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\LogLevel;
use Throwable;

trait LogExceptionTrait
{
    use LoggerAwareTrait;

    /**
     * Logs any exception with stack trace.
     *
     * @param Throwable $exception Any error to be logged.
     * @param string $logLevel Log message level.
     * @param array $context Additional log message context. By default the stack trace is in the key 'trace'.
     *
     * @return void
     */
    protected function logException(
        Throwable $exception,
        string $logLevel = LogLevel::ERROR,
        array $context = []
    ) {
        $this->logger->log(
            $logLevel,
            sprintf(
                "[%s] %s (%s)",
                get_class($exception),
                $exception->getMessage(),
                $exception->getCode()
            ),
            array_merge(
                $context,
                [
                    'trace' => $exception->getTraceAsString(),
                ]
            )
        );
    }
}
