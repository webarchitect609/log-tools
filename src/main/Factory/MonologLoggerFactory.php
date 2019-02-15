<?php

namespace WebArch\LogTools\Factory;

use InvalidArgumentException;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use WebArch\LogTools\Enum\SystemStream;

class MonologLoggerFactory
{
    const DEFAULT_SUB_FOLDER = 'main';

    /**
     * @var array
     */
    protected $loggerStack = [];

    /**
     * @var string
     */
    protected $logDir;

    /**
     * @var bool
     */
    protected $debug;

    /**
     * @var string
     */
    protected $defaultSubFolder;

    /**
     * @var int
     */
    protected $defaultLogLevel = Logger::INFO;

    /**
     * @var int
     */
    protected $debugLogLevel = Logger::DEBUG;

    public function __construct(string $logDir, bool $debug = false)
    {
        if (trim($logDir) === '') {
            throw new InvalidArgumentException('Empty log dir.');
        }
        $this->logDir = $logDir;
        $this->debug = $debug;
        $this->setDefaultSubFolder(self::DEFAULT_SUB_FOLDER);
    }

    /**
     * Creates logger with 'Y_m_d' timestamp as a filename.
     *
     * @param string $name Logger name to display in each log message. It does not affect the log filename.
     * @param string|null $subFolder sub-folder under log directory to keep log files.
     * @param int $additionalStream Allows to duplicate output to STDOUT or STDERR.
     *
     * @return LoggerInterface
     */
    public function createDailyLogger(
        string $name,
        string $subFolder = null,
        int $additionalStream = SystemStream::NONE
    ): LoggerInterface {
        if (is_null($subFolder)) {
            $subFolder = $this->defaultSubFolder;
        }

        /**
         * $name and $subFolder are not checked here
         * as logger with empty name or subFolder will never be created.
         */
        $loggerKey = $this->getLoggerKey($name, $subFolder);
        if (array_key_exists($loggerKey, $this->loggerStack)) {
            return $this->loggerStack[$loggerKey];
        }

        if ('' === trim($name)) {
            throw new InvalidArgumentException(
                'Empty logger name is not allowed.'
            );
        }
        if ('' === trim($subFolder)) {
            throw new InvalidArgumentException(
                'Empty subFolder name is not allowed.'
            );
        }

        $logFilename =
            $this->logDir . DIRECTORY_SEPARATOR .
            $subFolder . DIRECTORY_SEPARATOR .
            date('Y_m_d') . '.log';

        $logger = new Logger($name, [new StreamHandler($logFilename, $this->getLogLevel())]);

        $this->appendAdditionalStream($logger, $additionalStream);

        $this->loggerStack[$this->getLoggerKey($name, $subFolder)] = $logger;

        return $logger;
    }

    /**
     * @return string
     */
    public function getDefaultSubFolder(): string
    {
        return $this->defaultSubFolder;
    }

    /**
     * @param string $defaultSubFolder
     *
     * @return $this
     */
    public function setDefaultSubFolder(string $defaultSubFolder)
    {
        $this->defaultSubFolder = $defaultSubFolder;

        return $this;
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
    public function setDefaultLogLevel(int $defaultLogLevel)
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
    public function setDebugLogLevel(int $debugLogLevel)
    {
        $this->debugLogLevel = $debugLogLevel;

        return $this;
    }

    /**
     * Returns unique key for logger
     *
     * @param string $name
     * @param string $subFolder
     *
     * @return string
     */
    protected function getLoggerKey(string $name, string $subFolder): string
    {
        return trim($name) . '@' . trim($subFolder);
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
     * @param int $additionalStream
     *
     * @return void
     */
    protected function appendAdditionalStream(Logger $logger, int $additionalStream)
    {
        switch ($additionalStream) {

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
    }

}
