<?php

namespace WebArch\LogTools\Test\Fixture;

use InvalidArgumentException;
use LogicException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LogLevel;
use RuntimeException;
use Throwable;
use WebArch\LogTools\Traits\LogExceptionTrait;

class FooService implements LoggerAwareInterface
{
    use LogExceptionTrait;

    public function triggerExceptionCascade(?bool $enableChaining)
    {
        try {
            if (is_bool($enableChaining)) {
                $this->setChaining($enableChaining);
            }
            $this->triggerInvalidArgumentException();
        } catch (Throwable $exception) {
            $this->logException(
                $exception,
                LogLevel::EMERGENCY,
                ['foo' => 'bar']
            );
        }
    }

    private function triggerInvalidArgumentException()
    {
        try {
            $this->triggerRuntimeException();
        } catch (Throwable $exception) {
            throw new InvalidArgumentException(
                'Some argument is invalid',
                1,
                $exception
            );
        }
    }

    private function triggerRuntimeException()
    {
        try {
            $this->triggerLogicException();
        } catch (Throwable $exception) {
            throw new RuntimeException(
                'Something is not found',
                2,
                $exception
            );
        }
    }

    private function triggerLogicException()
    {
        throw new LogicException(
            'The error in the code',
            3
        );
    }
}
