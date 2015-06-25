<?php

namespace SWP\Exchange\Book;

use SimpleBus\Message\Handler\MessageHandler;
use SimpleBus\Message\Message;
use SimpleBus\Message\Recorder\RecordsMessages;
use SimpleES\EventSourcing\Aggregate\Manager\AggregateManager;
use SimpleES\EventSourcing\Aggregate\Manager\ManagesAggregates;
use SWP\Exchange\Command\AddBook;
use SWP\Exchange\Exception\InvalidCommandPassedToHandlerException;
use SWP\Exchange\Person\PersonId;

final class AddBookHandler implements MessageHandler
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
     * @param Message|AddBook $command
     */
    public function handle(Message $command)
    {
        if (!$command instanceof AddBook) {
            throw new InvalidCommandPassedToHandlerException($command, 'AddBook');
        }
        $book = Book::add(BookId::generate(), ISBN::fromString($command->getIsbn()), PersonId::fromString($command->getOwnerId()));

        $this->manager->save($book);
    }
}
