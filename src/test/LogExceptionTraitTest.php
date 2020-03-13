<?php

namespace WebArch\LogTools\Test;

use Exception;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use WebArch\LogTools\Test\Fixture\FooService;

class LogExceptionTraitTest extends TestCase
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
     * @var string
     */
    private $rootDir;

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

    public function testLogException()
    {
        /**
         * Don't move the triggerExceptionCascade() call, or the following expectations will be needed to change!
         */
        $this->fooService->triggerExceptionCascade();
        $expectedRecords = [
            [
                'message' => $this->replaceRoot(
                    '[InvalidArgumentException] Some argument is invalid (1) in %ROOT%/log-tools/src/test/Fixture/FooService.php:36'
                ),
                'context' => [
                    'foo'      => 'bar',
                    'trace'    => $this->replaceRoot(
                        '#0 %ROOT%/log-tools/src/test/Fixture/FooService.php(20): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerInvalidArgumentException()
#1 %ROOT%/log-tools/src/test/LogExceptionTraitTest.php(51): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerExceptionCascade()
#2 %ROOT%/log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(1415): WebArch\\LogTools\\Test\\LogExceptionTraitTest->testLogException()
#3 %ROOT%/log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(1035): PHPUnit\\Framework\\TestCase->runTest()
#4 %ROOT%/log-tools/vendor/phpunit/phpunit/src/Framework/TestResult.php(691): PHPUnit\\Framework\\TestCase->runBare()
#5 %ROOT%/log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(763): PHPUnit\\Framework\\TestResult->run(Object(WebArch\\LogTools\\Test\\LogExceptionTraitTest))
#6 %ROOT%/log-tools/vendor/phpunit/phpunit/src/Framework/TestSuite.php(597): PHPUnit\\Framework\\TestCase->run(Object(PHPUnit\\Framework\\TestResult))
#7 %ROOT%/log-tools/vendor/phpunit/phpunit/src/Framework/TestSuite.php(597): PHPUnit\\Framework\\TestSuite->run(Object(PHPUnit\\Framework\\TestResult))
#8 %ROOT%/log-tools/vendor/phpunit/phpunit/src/TextUI/TestRunner.php(621): PHPUnit\\Framework\\TestSuite->run(Object(PHPUnit\\Framework\\TestResult))
#9 %ROOT%/log-tools/vendor/phpunit/phpunit/src/TextUI/Command.php(204): PHPUnit\\TextUI\\TestRunner->doRun(Object(PHPUnit\\Framework\\TestSuite), Array, Array, true)
#10 %ROOT%/log-tools/vendor/phpunit/phpunit/src/TextUI/Command.php(163): PHPUnit\\TextUI\\Command->run(Array, true)
#11 %ROOT%/log-tools/vendor/phpunit/phpunit/phpunit(61): PHPUnit\\TextUI\\Command::main()
#12 {main}'
                    ),
                    'previous' => $this->replaceRoot(
                        '[RuntimeException] Something is not found (2) in %ROOT%/log-tools/src/test/Fixture/FooService.php:49'
                    ),
                ],
                'level'   => 600,
            ],
            [
                'message' => $this->replaceRoot(
                    '[RuntimeException] Something is not found (2) in %ROOT%/log-tools/src/test/Fixture/FooService.php:49'
                ),
                'context' => [
                    'foo'      => 'bar',
                    'trace'    => $this->replaceRoot(
                        '#0 %ROOT%/log-tools/src/test/Fixture/FooService.php(34): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerRuntimeException()
#1 %ROOT%/log-tools/src/test/Fixture/FooService.php(20): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerInvalidArgumentException()
#2 %ROOT%/log-tools/src/test/LogExceptionTraitTest.php(51): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerExceptionCascade()
#3 %ROOT%/log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(1415): WebArch\\LogTools\\Test\\LogExceptionTraitTest->testLogException()
#4 %ROOT%/log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(1035): PHPUnit\\Framework\\TestCase->runTest()
#5 %ROOT%/log-tools/vendor/phpunit/phpunit/src/Framework/TestResult.php(691): PHPUnit\\Framework\\TestCase->runBare()
#6 %ROOT%/log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(763): PHPUnit\\Framework\\TestResult->run(Object(WebArch\\LogTools\\Test\\LogExceptionTraitTest))
#7 %ROOT%/log-tools/vendor/phpunit/phpunit/src/Framework/TestSuite.php(597): PHPUnit\\Framework\\TestCase->run(Object(PHPUnit\\Framework\\TestResult))
#8 %ROOT%/log-tools/vendor/phpunit/phpunit/src/Framework/TestSuite.php(597): PHPUnit\\Framework\\TestSuite->run(Object(PHPUnit\\Framework\\TestResult))
#9 %ROOT%/log-tools/vendor/phpunit/phpunit/src/TextUI/TestRunner.php(621): PHPUnit\\Framework\\TestSuite->run(Object(PHPUnit\\Framework\\TestResult))
#10 %ROOT%/log-tools/vendor/phpunit/phpunit/src/TextUI/Command.php(204): PHPUnit\\TextUI\\TestRunner->doRun(Object(PHPUnit\\Framework\\TestSuite), Array, Array, true)
#11 %ROOT%/log-tools/vendor/phpunit/phpunit/src/TextUI/Command.php(163): PHPUnit\\TextUI\\Command->run(Array, true)
#12 %ROOT%/log-tools/vendor/phpunit/phpunit/phpunit(61): PHPUnit\\TextUI\\Command::main()
#13 {main}'
                    ),
                    'previous' => $this->replaceRoot(
                        '[LogicException] The error in the code (3) in %ROOT%/log-tools/src/test/Fixture/FooService.php:59'
                    ),
                ],
                'level'   => 600,
            ],
            [
                'message' => $this->replaceRoot(
                    '[LogicException] The error in the code (3) in %ROOT%/log-tools/src/test/Fixture/FooService.php:59'
                ),
                'context' => [
                    'foo'   => 'bar',
                    'trace' => $this->replaceRoot(
                        '#0 %ROOT%/log-tools/src/test/Fixture/FooService.php(47): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerLogicException()
#1 %ROOT%/log-tools/src/test/Fixture/FooService.php(34): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerRuntimeException()
#2 %ROOT%/log-tools/src/test/Fixture/FooService.php(20): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerInvalidArgumentException()
#3 %ROOT%/log-tools/src/test/LogExceptionTraitTest.php(51): WebArch\\LogTools\\Test\\Fixture\\FooService->triggerExceptionCascade()
#4 %ROOT%/log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(1415): WebArch\\LogTools\\Test\\LogExceptionTraitTest->testLogException()
#5 %ROOT%/log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(1035): PHPUnit\\Framework\\TestCase->runTest()
#6 %ROOT%/log-tools/vendor/phpunit/phpunit/src/Framework/TestResult.php(691): PHPUnit\\Framework\\TestCase->runBare()
#7 %ROOT%/log-tools/vendor/phpunit/phpunit/src/Framework/TestCase.php(763): PHPUnit\\Framework\\TestResult->run(Object(WebArch\\LogTools\\Test\\LogExceptionTraitTest))
#8 %ROOT%/log-tools/vendor/phpunit/phpunit/src/Framework/TestSuite.php(597): PHPUnit\\Framework\\TestCase->run(Object(PHPUnit\\Framework\\TestResult))
#9 %ROOT%/log-tools/vendor/phpunit/phpunit/src/Framework/TestSuite.php(597): PHPUnit\\Framework\\TestSuite->run(Object(PHPUnit\\Framework\\TestResult))
#10 %ROOT%/log-tools/vendor/phpunit/phpunit/src/TextUI/TestRunner.php(621): PHPUnit\\Framework\\TestSuite->run(Object(PHPUnit\\Framework\\TestResult))
#11 %ROOT%/log-tools/vendor/phpunit/phpunit/src/TextUI/Command.php(204): PHPUnit\\TextUI\\TestRunner->doRun(Object(PHPUnit\\Framework\\TestSuite), Array, Array, true)
#12 %ROOT%/log-tools/vendor/phpunit/phpunit/src/TextUI/Command.php(163): PHPUnit\\TextUI\\Command->run(Array, true)
#13 %ROOT%/log-tools/vendor/phpunit/phpunit/phpunit(61): PHPUnit\\TextUI\\Command::main()
#14 {main}'
                    ),
                ],
                'level'   => 600,
            ],

        ];

        foreach ($this->testHandler->getRecords() as $key => $record) {
            $expectedRecord = array_shift($expectedRecords);
            $this->assertEquals(
                $expectedRecord['message'],
                $record['message'],
                'Equal message for record #' . $key
            );
            $this->assertEquals(
                $expectedRecord['level'],
                $record['level'],
                'Equal level for record #' . $key
            );
            $this->assertEqualsCanonicalizing(
                $expectedRecord['context'],
                $record['context'],
                'Equal context for record #' . $key
            );
        }

        $this->assertTrue(true);
    }

    protected function replaceRoot(string $string): string
    {
        return str_replace(
            '%ROOT%',
            $this->rootDir,
            $string
        );
    }

}
