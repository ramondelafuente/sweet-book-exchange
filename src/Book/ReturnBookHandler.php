<?php

namespace SWP\Exchange\Book;

use SimpleBus\Message\Handler\MessageHandler;
use SimpleBus\Message\Message;
use SimpleBus\Message\Recorder\RecordsMessages;
use SimpleES\EventSourcing\Aggregate\Manager\AggregateManager;
use SimpleES\EventSourcing\Aggregate\Manager\ManagesAggregates;
use SWP\Exchange\Command\ReturnBook;
use SWP\Exchange\Exception\InvalidCommandPassedToHandlerException;

final class ReturnBookHandler implements MessageHandler
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
     * @param Message|ReturnBook $command
     */
    public function handle(Message $command)
    {
        if (!$command instanceof ReturnBook) {
            throw InvalidCommandPassedToHandlerException::make($command, 'ReturnBook');
        }

        /* @var Book $book */
        $book = $this->manager->fetch(BookId::fromString($command->getBookId()));
        $book->giveBack();

        $this->manager->save($book);
    }
}
