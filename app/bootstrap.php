<?php

include dirname(__DIR__) . '/vendor/autoload.php';

use Knp\Console\ConsoleEvent;
use Knp\Console\ConsoleEvents;
use Knp\Provider\ConsoleServiceProvider;
use SimpleBus\Message\Bus\Middleware\FinishesHandlingMessageBeforeHandlingNext;
use SimpleBus\Message\Handler\DelegatesToMessageHandlerMiddleware;
use SimpleBus\Message\Name\ClassBasedNameResolver;
use SimpleBus\Message\Recorder\HandlesRecordedMessagesMiddleware;
use SimpleBus\Message\Recorder\PublicMessageRecorder;
use SimpleBus\Message\Subscriber\NotifiesMessageSubscribersMiddleware;
use SimpleES\DoctrineDBALBridge\Event\Store\DBALEventStore;
use SimpleES\EventSourcing\Aggregate\Factory\MappingAggregateFactory;
use SimpleES\EventSourcing\Aggregate\Manager\AggregateManager;
use SimpleES\EventSourcing\Aggregate\Repository\AggregateRepository;
use SimpleES\EventSourcing\Event\NameResolver\MappingEventNameResolver;
use SimpleES\EventSourcing\Event\Wrapper\EventWrapper;
use SimpleES\EventSourcing\IdentityMap\IdentityMap;
use SimpleES\JMSSerializerBridge\Serializer\Serializer;
use SimpleES\MessageBusBridge\Event\Store\Decorator\MessageRecordingDecorator;
use SimpleES\MessageBusBridge\Name\EnvelopeBasedNameResolver;
use SWP\Exchange\Core\IdentifierGenerator;
use SWP\Exchange\Middleware\LogsCommands;
use SWP\Exchange\Middleware\LogsEvents;
use SWP\Exchange\Provider\CommandBusServiceProvider;
use SWP\Exchange\Provider\EventBusServiceProvider;

$app          = new Silex\Application();
$app['debug'] = true;

$app->register(new CommandBusServiceProvider());
$app->register(new EventBusServiceProvider());

$app->register(new Silex\Provider\MonologServiceProvider(), [
    'monolog.logfile' => __DIR__ . '/../var/logs/development.log',
]);

$app->register(
    new ConsoleServiceProvider(), [
        'console.name'              => 'SweetlakePHP Exchange',
        'console.version'           => '0.1.0',
        'console.project_directory' => __DIR__ . "/.."
    ]
);

$app->register(
    new Silex\Provider\DoctrineServiceProvider(), [
        'db.options' => [
            'driver'    => 'pdo_mysql',
            'host'      => 'localhost',
            'dbname'    => 'sweet-book-exchange',
            'user'      => 'sweetlakephp',
            'password'  => 'sweetlakephp',
            'charset'   => 'utf8',
        ]
    ]
);

$app['serializer'] = $app->share(function() {
    \Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
        'JMS\Serializer\Annotation',
        __DIR__ . "/../vendor/jms/serializer/src");

    $serializer = JMS\Serializer\SerializerBuilder::create()
        ->addMetadataDirs([
            'SWP\Exchange'              => __DIR__.  "/configs/serializer",
            'SimpleES\EventSourcing'    => __DIR__.  "/configs/serializer",
        ])
        ->build();

   return new Serializer($serializer, 'json');
});

$app['eventStore']      = $app->share(function ($app) {
    return new DBALEventStore($app['eventNameResolver'], $app['serializer'], $app['db'], 'events');
});

$app['messageRecordingEventStore']  = $app->share(function($app) {
    return new MessageRecordingDecorator($app['messageRecorder'], $app['eventStore']);
});

$app['aggregateFactory'] = $app->share(function() {
    $aggregateMap = [
        'SWP\Exchange\Book\BookId' => '\SWP\Exchange\Book\Book',
    ];
   return new MappingAggregateFactory($aggregateMap);
});

$app['identifierGenerator'] = $app->share(function(){
    return new IdentifierGenerator();
});

$app['eventNameResolver'] = $app->share(function(){
    return new MappingEventNameResolver(
        [
            'SWP\Exchange\Event\BookWasAdded'       => 'BookWasAdded',
            'SWP\Exchange\Event\BookWasBorrowed'    => 'BookWasBorrowed',
            'SWP\Exchange\Event\BookWasReturned'    => 'BookWasReturned',
            'SWP\Exchange\Event\BookWasDiscarded'   => 'BookWasDiscarded',
        ]
    );
});

$app['eventWrapper'] = $app->share(function($app) {
    return new EventWrapper($app['identifierGenerator'], $app['eventNameResolver'], 'SimpleES\MessageBusBridge\Event\Stream\EventEnvelope');
});

$app['repository'] = $app->share(function($app) {
    return new AggregateRepository($app['eventWrapper'], $app['messageRecordingEventStore'], $app['aggregateFactory']);
});

$app['identityMap'] = $app->share(function($app) {
    return new IdentityMap();
});

$app['aggregateManager'] = $app->share(function($app) {
    return new AggregateManager($app['identityMap'], $app['repository']);
});

$app['messageRecorder'] = $app->share(function () {
    return new PublicMessageRecorder();
});

$app['commandNameResolver'] = function () {
    return new ClassBasedNameResolver();
};

$app['envelopeNameResolver'] = function () {
    return new EnvelopeBasedNameResolver();
};

$app->extend('eventBus', function($eventBus, $app) {
    $eventBus->appendMiddleware(new LogsEvents($app['monolog']));
    $eventBus->appendMiddleware(
        new NotifiesMessageSubscribersMiddleware(
            $app['subscriberResolver']
        )
    );

    return $eventBus;
});

$app->extend('commandBus', function($commandBus, $app) {

    $commandBus->appendMiddleware(new HandlesRecordedMessagesMiddleware(
        $app['messageRecorder'],
        $app['eventBus']
    ));
    $commandBus->appendMiddleware(new LogsCommands($app['monolog']));
    $commandBus->appendMiddleware(new FinishesHandlingMessageBeforeHandlingNext());

    $commandBus->appendMiddleware(
        new DelegatesToMessageHandlerMiddleware(
            $app['commandHandlerResolver']
        )
    );

    return $commandBus;
});

$app['dispatcher']->addListener(ConsoleEvents::INIT, function (ConsoleEvent $event) {
    $app = $event->getApplication();
    $app->add(new SWP\Exchange\Console\Command\AddBook());
});

return $app;
