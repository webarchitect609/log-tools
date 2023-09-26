<?php

namespace WebArch\LogTools\Test\Factory;

use Exception;
use Monolog\Handler\StreamHandler;
use PHPUnit\Framework\TestCase;
use WebArch\LogTools\Factory\MonologLoggerFactory;
use function PHPUnit\Framework\assertEquals;

class MonologLoggerFactoryTest extends TestCase
{
    private MonologLoggerFactory $factory;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->factory = new MonologLoggerFactory(sys_get_temp_dir(), true);
    }

    /**
     * @throws Exception
     * @return void
     */
    public function testCreateFileLogger(): void
    {
        $loggerName = 'application';
        $loggerFilepath = 'main.log';
        $logger = $this->factory->createFileLogger($loggerName, $loggerFilepath);
        $handlers = $logger->getHandlers();
        self::assertCount(1, $handlers);
        $handler = reset($handlers);
        self::assertInstanceOf(StreamHandler::class, $handler);
        assertEquals(
            sys_get_temp_dir() . DIRECTORY_SEPARATOR . $loggerFilepath,
            $handler->getUrl()
        );
        assertEquals($loggerName, $logger->getName());
    }
}
