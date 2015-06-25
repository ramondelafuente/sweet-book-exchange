<?php

namespace SWP\Exchange\Provider;

use Pimple;
use Silex\Application;
use Silex\ServiceProviderInterface;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;
use SimpleBus\Message\Handler\Map\LazyLoadingMessageHandlerMap;
use SimpleBus\Message\Handler\Resolver\NameBasedMessageHandlerResolver;
use SWP\Exchange\Book\AddBookHandler;
use SWP\Exchange\Book\BorrowBookHandler;
use SWP\Exchange\Book\DiscardBookHandler;
use SWP\Exchange\Book\ReturnBookHandler;

class CommandbusServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['commandBus'] = function ($app) {
            $commandBus = new MessageBusSupportingMiddleware();

            return $commandBus;
        };

        $app['handlerLocator'] = function() use ($app) {
            $commandHandlers = $app['commandHandlers'];

            return function($handlerName) use ($commandHandlers) { return $commandHandlers[$handlerName]; };
        };

        $app['commandHandlerMap'] = function() use ($app) {
            $commandHandlerMap = new LazyLoadingMessageHandlerMap(
                [
                    'SWP\Exchange\Command\AddBook'     => 'AddBookHandler',
                    'SWP\Exchange\Command\BorrowBook'  => 'BorrowBookHandler',
                    'SWP\Exchange\Command\ReturnBook'  => 'ReturnBookHandler',
                    'SWP\Exchange\Command\DiscardBook' => 'DiscardBookHandler',
                ],
                $app['handlerLocator']
            );

            return $commandHandlerMap;
        };

        $app['commandHandlers'] = function() use ($app) {
            $commandHandlers = new Pimple();
            $commandHandlers['AddBookHandler'] = function() use ($app) {
                return new AddBookHandler($app['aggregateManager'], $app['messageRecorder']);
            };
            $commandHandlers['BorrowBookHandler'] = function() use ($app) {
                return new BorrowBookHandler($app['aggregateManager'], $app['messageRecorder']);
            };
            $commandHandlers['ReturnBookHandler'] = function() use ($app) {
                return new ReturnBookHandler($app['aggregateManager'], $app['messageRecorder']);
            };
            $commandHandlers['DiscardBookHandler'] = function() use ($app) {
                return new DiscardBookHandler($app['aggregateManager'], $app['messageRecorder']);
            };

            return $commandHandlers;
        };

        $app['commandHandlerResolver'] = function() use ($app) {
            return new NameBasedMessageHandlerResolver(
                $app['commandNameResolver'],
                $app['commandHandlerMap']
            );
        };

    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
    }
}
