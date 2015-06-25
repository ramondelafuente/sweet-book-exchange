<?php

namespace SWP\Exchange\Middleware;

use Monolog\Logger;
use SimpleBus\Message\Message;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;

class LogsCommands implements MessageBusMiddleware
{
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Message $message, callable $next)
    {
        $this->logger->addDebug('Testing the command logger Middleware: ' . get_class($message));
        $next($message);
    }
}
