<?php

namespace WebArch\LogTools\Factory;

use Exception;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use WebArch\LogTools\Enum\SystemStream;
use WebArch\LogTools\Exception\InvalidArgumentException;

class MonologLoggerFactory
{
    /**
     * @var array<string, Logger>
     */
    protected array $loggerStack = [];

    protected string $logDir;

    protected bool $debug;

    protected int $defaultLogLevel = Logger::INFO;

    protected int $debugLogLevel = Logger::DEBUG;

    public function __construct(string $logDir, bool $debug = false)
    {
        if (trim($logDir) === '') {
            throw new InvalidArgumentException('Empty log dir.');
        }
        $this->logDir = $logDir;
        $this->debug = $debug;
    }

    /**
     * Creates a logger which writes to the file $relativeFilePath
     *
     * @param string                  $name Logger name to display in each log message. It does not affect the log
     *     filename.
     * @param string                  $relativeFilePath Relative path and file name('foo/bar.log') or just
     *     filename('baz.log')
     * @param int                     $stdStream Allows to duplicate output to STDOUT or STDERR.
     * @param null|FormatterInterface $formatter
     *
     * @throws Exception
     * @return Logger
     */
    public function createFileLogger(
        string              $name,
        string              $relativeFilePath,
        int                 $stdStream = SystemStream::NONE,
        ?FormatterInterface $formatter = null
    ): Logger {
        $name = trim($name);
        $relativeFilePath = trim($relativeFilePath);
        /**
         * $name and $relativeFilePath are not checked here
         * as logger with empty name or relativeFilePath will never be created.
         */
        $loggerKey = $this->getLoggerKey($name, $relativeFilePath);
        if (key_exists($loggerKey, $this->loggerStack)) {
            return $this->loggerStack[$loggerKey];
        }
        if ('' === $name) {
            throw new InvalidArgumentException(
                'Empty logger name is not allowed.'
            );
        }
        if ('' === $relativeFilePath) {
            throw new InvalidArgumentException(
                'Empty logger filepath is not allowed.'
            );
        }
        if (strpos($relativeFilePath, DIRECTORY_SEPARATOR) === 0) {
            throw new InvalidArgumentException(
                sprintf(
                    'Logger filepath must be relative, not start with "%s".',
                    DIRECTORY_SEPARATOR
                )
            );
        }
        $this->loggerStack[$loggerKey] = $this->appendStdStream(
            new Logger(
                $name,
                [new StreamHandler($this->logDir . DIRECTORY_SEPARATOR . $relativeFilePath, $this->getLogLevel())]
            ),
            $stdStream
        );
        if ($formatter) {
            foreach ($this->loggerStack[$loggerKey]->getHandlers() as $handler) {
                $handler->setFormatter($formatter);
            }
        }

        return $this->loggerStack[$loggerKey];
    }

    /**
     * @return int
     */
    public function getDefaultLogLevel(): int
    {
        return $this->defaultLogLevel;
    }

    /**
     * @param int $defaultLogLevel
     *
     * @return $this
     */
    public function setDefaultLogLevel(int $defaultLogLevel): self
    {
        $this->defaultLogLevel = $defaultLogLevel;

        return $this;
    }

    /**
     * @return int
     */
    public function getDebugLogLevel(): int
    {
        return $this->debugLogLevel;
    }

    /**
     * @param int $debugLogLevel
     *
     * @return $this
     */
    public function setDebugLogLevel(int $debugLogLevel): self
    {
        $this->debugLogLevel = $debugLogLevel;

        return $this;
    }

    /**
     * @return string
     */
    protected function getLogLevel(): string
    {
        if ($this->debug) {
            return $this->getDebugLogLevel();
        }

        return $this->getDefaultLogLevel();
    }

    /**
     * @param Logger $logger
     * @param int    $stream
     *
     * @throws Exception
     * @return void
     */
    protected function appendStdStream(Logger $logger, int $stream): Logger
    {
        switch ($stream) {
            case SystemStream::STDOUT:
                if (defined('STDOUT') && is_resource(STDOUT)) {
                    $logger->pushHandler(new StreamHandler(STDOUT, $this->getLogLevel()));
                }
                break;
            case SystemStream::STDERR:
                if (defined('STDERR') && is_resource(STDERR)) {
                    $logger->pushHandler(new StreamHandler(STDERR, $this->getLogLevel()));
                }
                break;
        }

        return $logger;
    }

    /**
     * Returns unique key for logger
     *
     * @param string $name
     * @param string $relativeFilePath
     *
     * @return string
     */
    protected function getLoggerKey(string $name, string $relativeFilePath): string
    {
        return $name . '@' . $relativeFilePath;
    }
}
