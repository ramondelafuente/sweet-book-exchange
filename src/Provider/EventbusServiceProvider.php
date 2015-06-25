<?php

namespace SWP\Exchange\Provider;

use Pimple;
use Silex\Application;
use Silex\ServiceProviderInterface;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;
use SimpleBus\Message\Subscriber\Collection\LazyLoadingMessageSubscriberCollection;
use SimpleBus\Message\Subscriber\Resolver\NameBasedMessageSubscriberResolver;
use SWP\Exchange\Subscriber\AddBookToCatalog;
use SWP\Exchange\Subscriber\EnrichCatalogInformation;
use SWP\Exchange\Subscriber\RemoveBookFromCatalog;
use SWP\Exchange\Subscriber\UpdateCatalogAvailability;

class EventbusServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['eventBus'] = function() use ($app) {
            $eventBus = new MessageBusSupportingMiddleware();

            return $eventBus;
        };

        $app['eventLocator'] = function() use ($app) {
            $eventSubscribers = $app['eventSubscribers'];

            return function($serviceId) use ($eventSubscribers) { return $eventSubscribers[$serviceId]; };
        };

        $app['subscriberCollection'] = function() use ($app) {
            $subscriberCollection = new LazyLoadingMessageSubscriberCollection(
                [
                    'BookWasAdded'       => ['AddBookToCatalog', 'EnrichCatalogInformation'],
                    'BookWasBorrowed'    => ['UpdateCatalogAvailability'],
                    'BookWasReturned'    => ['UpdateCatalogAvailability'],
                    'BookWasDiscarded'   => ['RemoveBookFromCatalog'],
                ],
                $app['eventLocator']
            );

            return $subscriberCollection;
        };

        $app['eventSubscribers'] = function() use ($app) {
            $eventSubscribers = new Pimple();
            $eventSubscribers['AddBookToCatalog'] = function() use ($app) {
                return new AddBookToCatalog($app['db']);
            };
            $eventSubscribers['EnrichCatalogInformation'] = function() use ($app) {
                return new EnrichCatalogInformation($app['db']);
            };
            $eventSubscribers['RemoveBookFromCatalog'] = function() use ($app) {
                return new RemoveBookFromCatalog($app['db']);
            };
            $eventSubscribers['UpdateCatalogAvailability'] = function() use ($app) {
                return new UpdateCatalogAvailability($app['db']);
            };

            return $eventSubscribers;
        };

        $app['subscriberResolver'] = function() use ($app) {
            return new NameBasedMessageSubscriberResolver(
                $app['envelopeNameResolver'],
                $app['subscriberCollection']
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
