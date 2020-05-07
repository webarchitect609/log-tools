<?php

namespace WebArch\LogTools\Test\Fixture;

use PHPUnit\Framework\TestCase;

class LogExceptionFixture extends TestCase
{
    /**
     * @var string
     */
    protected $rootDir;

    /**
     * @param string $string
     *
     * @return string
     */
    protected function cleanUpString(string $string): string
    {
        return $this->removeCurrentDir(
            $this->removeLineNumber(
                $string
            )
        );
    }

    /**
     * Removes __DIR__ from the stack trace to simplify Unit-test.
     *
     * @param string $string
     *
     * @return string
     */
    protected function removeCurrentDir(string $string): string
    {
        return str_replace(
            $this->rootDir,
            '',
            $string
        );
    }

    /**
     * Removes line number in the stack trace or in the string representation of the exception to simplify Unit-test.
     *
     * @param string $string
     *
     * @return string
     */
    protected function removeLineNumber(string $string): string
    {
        $string = preg_replace(
            '/(\.php\()\d+(\):)/',
            '${1}*${2}',
            $string
        );

        return preg_replace(
            '/(\.php:)\d+/',
            '${1}*',
            $string
        );
    }

    /**
     * @param array $array
     *
     * @return array
     */
    protected function cleanUpArray(array $array): array
    {
        $modifiedArray = $array;
        array_walk_recursive(
            $modifiedArray,
            function (&$item) {
                if (is_string($item)) {
                    $item = $this->cleanUpString($item);
                }
            }
        );

        return $modifiedArray;
    }
}
