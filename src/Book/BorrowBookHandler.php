<?php

namespace SWP\Exchange\Book;

use SimpleBus\Message\Handler\MessageHandler;
use SimpleBus\Message\Message;
use SimpleBus\Message\Recorder\RecordsMessages;
use SimpleES\EventSourcing\Aggregate\Manager\AggregateManager;
use SimpleES\EventSourcing\Aggregate\Manager\ManagesAggregates;
use SimpleES\EventSourcing\Event\Store\StoresEvents;
use SWP\Exchange\Command\BorrowBook;
use SWP\Exchange\Exception\InvalidCommandPassedToHandlerException;
use SWP\Exchange\Person\PersonId;

final class BorrowBookHandler implements MessageHandler
{
    /**
     * @var ManagesAggregates
     */
    private $manager;

    /**
     * @var RecordsMessages
     */
    private $messageRecorder;

    public function __construct(AggregateManager $manager, RecordsMessages $messageRecorder)
    {
        $this->manager      = $manager;
        $this->messageRecorder = $messageRecorder;
    }

    /**
     * @param Message|BorrowBook $command
     */
    public function handle(Message $command)
    {
        if (!$command instanceof BorrowBook) {
            throw InvalidCommandPassedToHandlerException::make($command, 'BorrowBook');
        }

        /* @var Book $book */
        $book = $this->manager->fetch(BookId::fromString($command->getBookId()));
        $book->borrow(PersonId::fromString($command->getBorrowerId()));

        $this->manager->save($book);
    }
}
