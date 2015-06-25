<?php

namespace SWP\Exchange\Exception;

use SWP\Exchange\Core\Command;

class InvalidCommandPassedToHandlerException extends \InvalidArgumentException implements Exception
{
    static protected $template = 'Invalid command type %s passed, handler expected %s';

    public static function make(Command $actual, $expected)
    {
        return new static(sprintf(static::$template, get_class($actual), $expected));
    }
}
