<?php

namespace WebArch\LogTools\Exception;

use InvalidArgumentException as CommonInvalidArgumentException;

class InvalidArgumentException extends CommonInvalidArgumentException implements LogToolsExceptionInterface
{
}
