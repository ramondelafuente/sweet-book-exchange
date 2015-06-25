<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/bootstrap.php';

use SWP\Exchange\Command\AddBook;
use SWP\Exchange\Command\BorrowBook;
use SWP\Exchange\Command\DiscardBook;
use SWP\Exchange\Command\ReturnBook;
use SWP\Exchange\Person\PersonId;

$app->get('/', function (Silex\Application $app) {
    $statement = $app['db']->query('SELECT * FROM CATALOG');
    return '<pre>' . print_r($statement->fetchAll(), true) . '</pre>';
});

$app->get('/add/{isbn}', function (Silex\Application $app, $isbn) {
    $command = new AddBook($isbn, PersonId::generate());
    $app['commandBus']->handle($command);

    return 'Added';
});

$app->get('/borrow/{bookId}', function (Silex\Application $app, $bookId) {
    $command = new BorrowBook($bookId, PersonId::generate());
    $app['commandBus']->handle($command);

    return 'Borrowed';
});
$app->get('/return/{bookId}', function (Silex\Application $app, $bookId) {
    $command = new ReturnBook($bookId);
    $app['commandBus']->handle($command);

    return 'Returned';
});
$app->get('/discard/{bookId}', function (Silex\Application $app, $bookId) {
    $command = new DiscardBook($bookId);
    $app['commandBus']->handle($command);

    return 'Discarded';
});

$app->run();

