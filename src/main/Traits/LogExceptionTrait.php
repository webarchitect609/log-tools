<?php /** @noinspection PhpUnused */

namespace WebArch\LogTools\Traits;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\LogLevel;
use Throwable;

trait LogExceptionTrait
{
    use LoggerAwareTrait;

    /**
     * Logs any exception properly: with the stack trace, with all previous exceptions, etc.
     *
     * @param Throwable $exception Any error to be logged.
     * @param string $logLevel Log message level.
     * @param array $context Additional log message context. By default the stack trace is in the key 'trace'.
     * @param bool $enableChaining If true, all the exception chain will be logged.
     *
     * @return void
     */
    protected function logException(
        Throwable $exception,
        string $logLevel = LogLevel::ERROR,
        array $context = [],
        bool $enableChaining = false
    ) {
        $this->logger->log(
            $logLevel,
            $this->getExceptionAsString($exception),
            array_merge(
                $context,
                $this->getContext($exception)
            )
        );
        if (true === $enableChaining && $exception->getPrevious() instanceof Throwable) {
            $this->logException(
                $exception->getPrevious(),
                $logLevel,
                $context,
                $enableChaining
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
        $context = [
            'trace' => $exception->getTraceAsString(),
        ];
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
}
