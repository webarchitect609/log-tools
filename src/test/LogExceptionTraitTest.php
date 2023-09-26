<?php

namespace WebArch\LogTools\Test;

use Exception;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Psr\Log\LogLevel;
use WebArch\LogTools\Test\Fixture\FooService;
use WebArch\LogTools\Test\Fixture\LogExceptionFixture;

class LogExceptionTraitTest extends LogExceptionFixture
{
    private const DUMMY_TRACE = 'trace';

    /**
     * @var FooService
     */
    private FooService $fooService;

    /**
     * @var TestHandler
     */
    private TestHandler $testHandler;

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->rootDir = realpath(__DIR__ . '/../../../');
        $this->fooService = new FooService();
        $this->testHandler = new TestHandler(LogLevel::DEBUG);
        $this->fooService->setLogger(
            new Logger(
                'TestLogger',
                [$this->testHandler]
            )
        );
    }

    public function testLogExceptionDefaultChaining(): void
    {
        $this->fooService->triggerExceptionCascade(null);

        $expectedRecords = [
            [
                'message'    => '[InvalidArgumentException] Some argument is invalid (1) in /log-tools/src/test/Fixture/FooService.php:*',
                'context'    => [
                    'foo'      => 'bar',
                    'trace'    => [self::DUMMY_TRACE],
                    'previous' => '[RuntimeException] Something is not found (2) in /log-tools/src/test/Fixture/FooService.php:*',
                ],
                'level_name' => 'EMERGENCY',
            ],
            [
                'message'    => '[RuntimeException] Something is not found (2) in /log-tools/src/test/Fixture/FooService.php:*',
                'context'    => [
                    'foo'      => 'bar',
                    'trace'    => [self::DUMMY_TRACE],
                    'previous' => '[LogicException] The error in the code (3) in /log-tools/src/test/Fixture/FooService.php:*',
                ],
                'level_name' => 'EMERGENCY',
            ],
            [
                'message'    => '[LogicException] The error in the code (3) in /log-tools/src/test/Fixture/FooService.php:*',
                'context'    => [
                    'foo'   => 'bar',
                    'trace' => [self::DUMMY_TRACE],
                ],
                'level_name' => 'EMERGENCY',
            ],

        ];
        $this->assertLogRecords($expectedRecords);
    }

    public function testLogExceptionChainingEnabled(): void
    {
        $this->fooService->triggerExceptionCascade(true);

        $expectedRecords = [
            [
                'message'    => '[InvalidArgumentException] Some argument is invalid (1) in /log-tools/src/test/Fixture/FooService.php:*',
                'context'    => [
                    'foo'      => 'bar',
                    'trace'    => [self::DUMMY_TRACE],
                    'previous' => '[RuntimeException] Something is not found (2) in /log-tools/src/test/Fixture/FooService.php:*',
                ],
                'level_name' => 'EMERGENCY',
            ],
            [
                'message'    => '[RuntimeException] Something is not found (2) in /log-tools/src/test/Fixture/FooService.php:*',
                'context'    => [
                    'foo'      => 'bar',
                    'trace'    => [self::DUMMY_TRACE],
                    'previous' => '[LogicException] The error in the code (3) in /log-tools/src/test/Fixture/FooService.php:*',
                ],
                'level_name' => 'EMERGENCY',
            ],
            [
                'message'    => '[LogicException] The error in the code (3) in /log-tools/src/test/Fixture/FooService.php:*',
                'context'    => [
                    'foo'   => 'bar',
                    'trace' => [self::DUMMY_TRACE],
                ],
                'level_name' => 'EMERGENCY',
            ],

        ];
        $this->assertLogRecords($expectedRecords);
    }

    public function testLogExceptionChainingDisabled(): void
    {
        $this->fooService->triggerExceptionCascade(false);

        $expectedRecords = [
            [
                'message'    => '[InvalidArgumentException] Some argument is invalid (1) in /log-tools/src/test/Fixture/FooService.php:*',
                'context'    => [
                    'foo'      => 'bar',
                    'trace'    => [self::DUMMY_TRACE],
                    'previous' => '[RuntimeException] Something is not found (2) in /log-tools/src/test/Fixture/FooService.php:*',
                ],
                'level_name' => 'EMERGENCY',
            ],
        ];
        $this->assertLogRecords($expectedRecords);
    }

    /**
     * @param array $expectedRecords
     *
     * @return void
     */
    protected function assertLogRecords(array $expectedRecords): void
    {
        foreach ($this->testHandler->getRecords() as $key => $record) {
            $expectedRecord = array_shift($expectedRecords);
            $this->assertEquals(
                $expectedRecord['message'],
                $this->cleanUpString($record['message']),
                'Equal message for record #' . $key
            );
            $this->assertEquals(
                $expectedRecord['level_name'],
                $record['level_name'],
                'Equal level for record #' . $key
            );
            // Simplify 'trace' since it depends on the way the test is run
            if (key_exists('trace', $record['context'])) {
                $record['context']['trace'] = [self::DUMMY_TRACE];
            }
            $this->assertEqualsCanonicalizing(
                $expectedRecord['context'],
                $this->cleanUpArray($record['context']),
                'Equal context for record #' . $key
            );
        }
    }

}
