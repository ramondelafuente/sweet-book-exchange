<?php

namespace SWP\Exchange\Book;

use SimpleBus\Message\Handler\MessageHandler;
use SimpleBus\Message\Message;
use SimpleBus\Message\Recorder\RecordsMessages;
use SimpleES\EventSourcing\Aggregate\Manager\AggregateManager;
use SimpleES\EventSourcing\Aggregate\Manager\ManagesAggregates;
use SWP\Exchange\Command\DiscardBook;
use SWP\Exchange\Exception\InvalidCommandPassedToHandlerException;

final class DiscardBookHandler implements MessageHandler
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
     * @param Message|DiscardBook $command
     */
    public function handle(Message $command)
    {
        if (!$command instanceof DiscardBook) {
            throw InvalidCommandPassedToHandlerException::make($command, 'DiscardBook');
        }

        /* @var Book $book */
        $book = $this->manager->fetch(BookId::fromString($command->getBookId()));
        $book->discard();

        $this->manager->save($book);
    }
}
