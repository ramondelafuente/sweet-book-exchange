<?php

include dirname(__DIR__) .'/vendor/autoload.php';

use Knp\Console\ConsoleEvents;
use Knp\Console\ConsoleEvent;
use Knp\Provider\ConsoleServiceProvider;

$app = new Silex\Application(['debug' => true]);

$app->register(
    new ConsoleServiceProvider(),
    [
        'console.name' => 'SweetlakePHP Exchange',
        'console.version' => '0.1.0',
        'console.project_directory' => __DIR__ . "/.."
    ]
);

$app['dispatcher']->addListener(ConsoleEvents::INIT, function(ConsoleEvent $event) {
    $app = $event->getApplication();
    $app->add(new SWP\Exchange\Console\Command\AddBook());
});

return $app;