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
    /**
     * @var FooService
     */
    private $fooService;

    /**
     * @var TestHandler
     */
    private $testHandler;

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

    public function testLogExceptionDefaultChaining()
    {
        $this->fooService->triggerExceptionCascade(null);

        $expectedRecords = [
            [
                'message' => '[InvalidArgumentException] Some argument is invalid (1) in /log-tools/src/test/Fixture/FooService.php:*',
                'context' => [
                    'foo'      => 'bar',
                    'trace'    =>
                        [
                            0  => '#0 /log-tools/src/test/Fixture/FooService.php(*): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerInvalidArgumentException()',
                            1  => '#1 /log-tools/src/test/LogExceptionTraitTest.php(*): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerExceptionCascade(NULL)',
                            2  => '#2 /log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(*): WebArch\\LogTools\\Test\\LogExceptionTraitTest->testLogExceptionDefaultChaining()',
                            3  => '#3 /log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(*): PHPUnit\\Framework\\TestCase->runTest()',
                            4  => '#4 /log-tools/vendor/phpunit/phpunit/src/Framework/TestResult.php(*): PHPUnit\\Framework\\TestCase->runBare()',
                            5  => '#5 /log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(*): PHPUnit\\Framework\\TestResult->run(Object(WebArch\\LogTools\\Test\\LogExceptionTraitTest))',
                            6  => '#6 /log-tools/vendor/phpunit/phpunit/src/Framework/TestSuite.php(*): PHPUnit\\Framework\\TestCase->run(Object(PHPUnit\\Framework\\TestResult))',
                            7  => '#7 /log-tools/vendor/phpunit/phpunit/src/Framework/TestSuite.php(*): PHPUnit\\Framework\\TestSuite->run(Object(PHPUnit\\Framework\\TestResult))',
                            8  => '#8 /log-tools/vendor/phpunit/phpunit/src/TextUI/TestRunner.php(*): PHPUnit\\Framework\\TestSuite->run(Object(PHPUnit\\Framework\\TestResult))',
                            9  => '#9 /log-tools/vendor/phpunit/phpunit/src/TextUI/Command.php(*): PHPUnit\\TextUI\\TestRunner->doRun(Object(PHPUnit\\Framework\\TestSuite), Array, Array, true)',
                            10 => '#10 /log-tools/vendor/phpunit/phpunit/src/TextUI/Command.php(*): PHPUnit\\TextUI\\Command->run(Array, true)',
                            11 => '#11 /log-tools/vendor/phpunit/phpunit/phpunit(61): PHPUnit\\TextUI\\Command::main()',
                            12 => '#12 {main}',
                        ],
                    'previous' => '[RuntimeException] Something is not found (2) in /log-tools/src/test/Fixture/FooService.php:*',
                ],
                'level_name'   => 'EMERGENCY',
            ],
            [
                'message' => '[RuntimeException] Something is not found (2) in /log-tools/src/test/Fixture/FooService.php:*',
                'context' => [
                    'foo'      => 'bar',
                    'trace'    =>
                        [
                            0  => '#0 /log-tools/src/test/Fixture/FooService.php(*): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerRuntimeException()',
                            1  => '#1 /log-tools/src/test/Fixture/FooService.php(*): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerInvalidArgumentException()',
                            2  => '#2 /log-tools/src/test/LogExceptionTraitTest.php(*): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerExceptionCascade(NULL)',
                            3  => '#3 /log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(*): WebArch\\LogTools\\Test\\LogExceptionTraitTest->testLogExceptionDefaultChaining()',
                            4  => '#4 /log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(*): PHPUnit\\Framework\\TestCase->runTest()',
                            5  => '#5 /log-tools/vendor/phpunit/phpunit/src/Framework/TestResult.php(*): PHPUnit\\Framework\\TestCase->runBare()',
                            6  => '#6 /log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(*): PHPUnit\\Framework\\TestResult->run(Object(WebArch\\LogTools\\Test\\LogExceptionTraitTest))',
                            7  => '#7 /log-tools/vendor/phpunit/phpunit/src/Framework/TestSuite.php(*): PHPUnit\\Framework\\TestCase->run(Object(PHPUnit\\Framework\\TestResult))',
                            8  => '#8 /log-tools/vendor/phpunit/phpunit/src/Framework/TestSuite.php(*): PHPUnit\\Framework\\TestSuite->run(Object(PHPUnit\\Framework\\TestResult))',
                            9  => '#9 /log-tools/vendor/phpunit/phpunit/src/TextUI/TestRunner.php(*): PHPUnit\\Framework\\TestSuite->run(Object(PHPUnit\\Framework\\TestResult))',
                            10 => '#10 /log-tools/vendor/phpunit/phpunit/src/TextUI/Command.php(*): PHPUnit\\TextUI\\TestRunner->doRun(Object(PHPUnit\\Framework\\TestSuite), Array, Array, true)',
                            11 => '#11 /log-tools/vendor/phpunit/phpunit/src/TextUI/Command.php(*): PHPUnit\\TextUI\\Command->run(Array, true)',
                            12 => '#12 /log-tools/vendor/phpunit/phpunit/phpunit(61): PHPUnit\\TextUI\\Command::main()',
                            13 => '#13 {main}',
                        ],
                    'previous' => '[LogicException] The error in the code (3) in /log-tools/src/test/Fixture/FooService.php:*',
                ],
                'level_name'   => 'EMERGENCY',
            ],
            [
                'message' => '[LogicException] The error in the code (3) in /log-tools/src/test/Fixture/FooService.php:*',
                'context' => [
                    'foo'   => 'bar',
                    'trace' =>
                        [
                            0  => '#0 /log-tools/src/test/Fixture/FooService.php(*): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerLogicException()',
                            1  => '#1 /log-tools/src/test/Fixture/FooService.php(*): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerRuntimeException()',
                            2  => '#2 /log-tools/src/test/Fixture/FooService.php(*): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerInvalidArgumentException()',
                            3  => '#3 /log-tools/src/test/LogExceptionTraitTest.php(*): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerExceptionCascade(NULL)',
                            4  => '#4 /log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(*): WebArch\\LogTools\\Test\\LogExceptionTraitTest->testLogExceptionDefaultChaining()',
                            5  => '#5 /log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(*): PHPUnit\\Framework\\TestCase->runTest()',
                            6  => '#6 /log-tools/vendor/phpunit/phpunit/src/Framework/TestResult.php(*): PHPUnit\\Framework\\TestCase->runBare()',
                            7  => '#7 /log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(*): PHPUnit\\Framework\\TestResult->run(Object(WebArch\\LogTools\\Test\\LogExceptionTraitTest))',
                            8  => '#8 /log-tools/vendor/phpunit/phpunit/src/Framework/TestSuite.php(*): PHPUnit\\Framework\\TestCase->run(Object(PHPUnit\\Framework\\TestResult))',
                            9  => '#9 /log-tools/vendor/phpunit/phpunit/src/Framework/TestSuite.php(*): PHPUnit\\Framework\\TestSuite->run(Object(PHPUnit\\Framework\\TestResult))',
                            10 => '#10 /log-tools/vendor/phpunit/phpunit/src/TextUI/TestRunner.php(*): PHPUnit\\Framework\\TestSuite->run(Object(PHPUnit\\Framework\\TestResult))',
                            11 => '#11 /log-tools/vendor/phpunit/phpunit/src/TextUI/Command.php(*): PHPUnit\\TextUI\\TestRunner->doRun(Object(PHPUnit\\Framework\\TestSuite), Array, Array, true)',
                            12 => '#12 /log-tools/vendor/phpunit/phpunit/src/TextUI/Command.php(*): PHPUnit\\TextUI\\Command->run(Array, true)',
                            13 => '#13 /log-tools/vendor/phpunit/phpunit/phpunit(61): PHPUnit\\TextUI\\Command::main()',
                            14 => '#14 {main}',
                        ],
                ],
                'level_name'   => 'EMERGENCY',
            ],

        ];
        $this->assertLogRecords($expectedRecords);
    }

    public function testLogExceptionChainingEnabled()
    {
        $this->fooService->triggerExceptionCascade(true);

        $expectedRecords = [
            [
                'message' => '[InvalidArgumentException] Some argument is invalid (1) in /log-tools/src/test/Fixture/FooService.php:*',
                'context' => [
                    'foo'      => 'bar',
                    'trace'    =>
                        [
                            0  => '#0 /log-tools/src/test/Fixture/FooService.php(*): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerInvalidArgumentException()',
                            1  => '#1 /log-tools/src/test/LogExceptionTraitTest.php(*): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerExceptionCascade(true)',
                            2  => '#2 /log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(*): WebArch\\LogTools\\Test\\LogExceptionTraitTest->testLogExceptionChainingEnabled()',
                            3  => '#3 /log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(*): PHPUnit\\Framework\\TestCase->runTest()',
                            4  => '#4 /log-tools/vendor/phpunit/phpunit/src/Framework/TestResult.php(*): PHPUnit\\Framework\\TestCase->runBare()',
                            5  => '#5 /log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(*): PHPUnit\\Framework\\TestResult->run(Object(WebArch\\LogTools\\Test\\LogExceptionTraitTest))',
                            6  => '#6 /log-tools/vendor/phpunit/phpunit/src/Framework/TestSuite.php(*): PHPUnit\\Framework\\TestCase->run(Object(PHPUnit\\Framework\\TestResult))',
                            7  => '#7 /log-tools/vendor/phpunit/phpunit/src/Framework/TestSuite.php(*): PHPUnit\\Framework\\TestSuite->run(Object(PHPUnit\\Framework\\TestResult))',
                            8  => '#8 /log-tools/vendor/phpunit/phpunit/src/TextUI/TestRunner.php(*): PHPUnit\\Framework\\TestSuite->run(Object(PHPUnit\\Framework\\TestResult))',
                            9  => '#9 /log-tools/vendor/phpunit/phpunit/src/TextUI/Command.php(*): PHPUnit\\TextUI\\TestRunner->doRun(Object(PHPUnit\\Framework\\TestSuite), Array, Array, true)',
                            10 => '#10 /log-tools/vendor/phpunit/phpunit/src/TextUI/Command.php(*): PHPUnit\\TextUI\\Command->run(Array, true)',
                            11 => '#11 /log-tools/vendor/phpunit/phpunit/phpunit(61): PHPUnit\\TextUI\\Command::main()',
                            12 => '#12 {main}',
                        ],
                    'previous' => '[RuntimeException] Something is not found (2) in /log-tools/src/test/Fixture/FooService.php:*',
                ],
                'level_name'   => 'EMERGENCY',
            ],
            [
                'message' => '[RuntimeException] Something is not found (2) in /log-tools/src/test/Fixture/FooService.php:*',
                'context' => [
                    'foo'      => 'bar',
                    'trace'    =>
                        [
                            0  => '#0 /log-tools/src/test/Fixture/FooService.php(*): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerRuntimeException()',
                            1  => '#1 /log-tools/src/test/Fixture/FooService.php(*): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerInvalidArgumentException()',
                            2  => '#2 /log-tools/src/test/LogExceptionTraitTest.php(*): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerExceptionCascade(true)',
                            3  => '#3 /log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(*): WebArch\\LogTools\\Test\\LogExceptionTraitTest->testLogExceptionChainingEnabled()',
                            4  => '#4 /log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(*): PHPUnit\\Framework\\TestCase->runTest()',
                            5  => '#5 /log-tools/vendor/phpunit/phpunit/src/Framework/TestResult.php(*): PHPUnit\\Framework\\TestCase->runBare()',
                            6  => '#6 /log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(*): PHPUnit\\Framework\\TestResult->run(Object(WebArch\\LogTools\\Test\\LogExceptionTraitTest))',
                            7  => '#7 /log-tools/vendor/phpunit/phpunit/src/Framework/TestSuite.php(*): PHPUnit\\Framework\\TestCase->run(Object(PHPUnit\\Framework\\TestResult))',
                            8  => '#8 /log-tools/vendor/phpunit/phpunit/src/Framework/TestSuite.php(*): PHPUnit\\Framework\\TestSuite->run(Object(PHPUnit\\Framework\\TestResult))',
                            9  => '#9 /log-tools/vendor/phpunit/phpunit/src/TextUI/TestRunner.php(*): PHPUnit\\Framework\\TestSuite->run(Object(PHPUnit\\Framework\\TestResult))',
                            10 => '#10 /log-tools/vendor/phpunit/phpunit/src/TextUI/Command.php(*): PHPUnit\\TextUI\\TestRunner->doRun(Object(PHPUnit\\Framework\\TestSuite), Array, Array, true)',
                            11 => '#11 /log-tools/vendor/phpunit/phpunit/src/TextUI/Command.php(*): PHPUnit\\TextUI\\Command->run(Array, true)',
                            12 => '#12 /log-tools/vendor/phpunit/phpunit/phpunit(61): PHPUnit\\TextUI\\Command::main()',
                            13 => '#13 {main}',
                        ],
                    'previous' => '[LogicException] The error in the code (3) in /log-tools/src/test/Fixture/FooService.php:*',
                ],
                'level_name'   => 'EMERGENCY',
            ],
            [
                'message' => '[LogicException] The error in the code (3) in /log-tools/src/test/Fixture/FooService.php:*',
                'context' => [
                    'foo'   => 'bar',
                    'trace' =>
                        [
                            0  => '#0 /log-tools/src/test/Fixture/FooService.php(*): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerLogicException()',
                            1  => '#1 /log-tools/src/test/Fixture/FooService.php(*): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerRuntimeException()',
                            2  => '#2 /log-tools/src/test/Fixture/FooService.php(*): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerInvalidArgumentException()',
                            3  => '#3 /log-tools/src/test/LogExceptionTraitTest.php(*): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerExceptionCascade(true)',
                            4  => '#4 /log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(*): WebArch\\LogTools\\Test\\LogExceptionTraitTest->testLogExceptionChainingEnabled()',
                            5  => '#5 /log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(*): PHPUnit\\Framework\\TestCase->runTest()',
                            6  => '#6 /log-tools/vendor/phpunit/phpunit/src/Framework/TestResult.php(*): PHPUnit\\Framework\\TestCase->runBare()',
                            7  => '#7 /log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(*): PHPUnit\\Framework\\TestResult->run(Object(WebArch\\LogTools\\Test\\LogExceptionTraitTest))',
                            8  => '#8 /log-tools/vendor/phpunit/phpunit/src/Framework/TestSuite.php(*): PHPUnit\\Framework\\TestCase->run(Object(PHPUnit\\Framework\\TestResult))',
                            9  => '#9 /log-tools/vendor/phpunit/phpunit/src/Framework/TestSuite.php(*): PHPUnit\\Framework\\TestSuite->run(Object(PHPUnit\\Framework\\TestResult))',
                            10 => '#10 /log-tools/vendor/phpunit/phpunit/src/TextUI/TestRunner.php(*): PHPUnit\\Framework\\TestSuite->run(Object(PHPUnit\\Framework\\TestResult))',
                            11 => '#11 /log-tools/vendor/phpunit/phpunit/src/TextUI/Command.php(*): PHPUnit\\TextUI\\TestRunner->doRun(Object(PHPUnit\\Framework\\TestSuite), Array, Array, true)',
                            12 => '#12 /log-tools/vendor/phpunit/phpunit/src/TextUI/Command.php(*): PHPUnit\\TextUI\\Command->run(Array, true)',
                            13 => '#13 /log-tools/vendor/phpunit/phpunit/phpunit(61): PHPUnit\\TextUI\\Command::main()',
                            14 => '#14 {main}',
                        ],
                ],
                'level_name'   => 'EMERGENCY',
            ],

        ];
        $this->assertLogRecords($expectedRecords);
    }

    public function testLogExceptionChainingDisabled()
    {
        $this->fooService->triggerExceptionCascade(false);

        $expectedRecords = [
            [
                'message' => '[InvalidArgumentException] Some argument is invalid (1) in /log-tools/src/test/Fixture/FooService.php:*',
                'context' => [
                    'foo'      => 'bar',
                    'trace'    =>
                        [
                            0  => '#0 /log-tools/src/test/Fixture/FooService.php(*): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerInvalidArgumentException()',
                            1  => '#1 /log-tools/src/test/LogExceptionTraitTest.php(*): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerExceptionCascade(false)',
                            2  => '#2 /log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(*): WebArch\\LogTools\\Test\\LogExceptionTraitTest->testLogExceptionChainingDisabled()',
                            3  => '#3 /log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(*): PHPUnit\\Framework\\TestCase->runTest()',
                            4  => '#4 /log-tools/vendor/phpunit/phpunit/src/Framework/TestResult.php(*): PHPUnit\\Framework\\TestCase->runBare()',
                            5  => '#5 /log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(*): PHPUnit\\Framework\\TestResult->run(Object(WebArch\\LogTools\\Test\\LogExceptionTraitTest))',
                            6  => '#6 /log-tools/vendor/phpunit/phpunit/src/Framework/TestSuite.php(*): PHPUnit\\Framework\\TestCase->run(Object(PHPUnit\\Framework\\TestResult))',
                            7  => '#7 /log-tools/vendor/phpunit/phpunit/src/Framework/TestSuite.php(*): PHPUnit\\Framework\\TestSuite->run(Object(PHPUnit\\Framework\\TestResult))',
                            8  => '#8 /log-tools/vendor/phpunit/phpunit/src/TextUI/TestRunner.php(*): PHPUnit\\Framework\\TestSuite->run(Object(PHPUnit\\Framework\\TestResult))',
                            9  => '#9 /log-tools/vendor/phpunit/phpunit/src/TextUI/Command.php(*): PHPUnit\\TextUI\\TestRunner->doRun(Object(PHPUnit\\Framework\\TestSuite), Array, Array, true)',
                            10 => '#10 /log-tools/vendor/phpunit/phpunit/src/TextUI/Command.php(*): PHPUnit\\TextUI\\Command->run(Array, true)',
                            11 => '#11 /log-tools/vendor/phpunit/phpunit/phpunit(61): PHPUnit\\TextUI\\Command::main()',
                            12 => '#12 {main}',
                        ],
                    'previous' => '[RuntimeException] Something is not found (2) in /log-tools/src/test/Fixture/FooService.php:*',
                ],
                'level_name'   => 'EMERGENCY',
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
            $this->assertEqualsCanonicalizing(
                $expectedRecord['context'],
                $this->cleanUpArray($record['context']),
                'Equal context for record #' . $key
            );
        }
    }

}
