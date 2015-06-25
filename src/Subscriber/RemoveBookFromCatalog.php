<?php

namespace SWP\Exchange\Subscriber;

use Doctrine\DBAL\Driver\Connection;
use SimpleBus\Message\Message;
use SimpleBus\Message\Subscriber\MessageSubscriber;

class RemoveBookFromCatalog implements MessageSubscriber
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function notify(Message $envelope) {
        $message = $envelope->event();

        $sql = <<< EOQ
UPDATE
    catalog
SET
    available_copies = available_copies-1
WHERE
    isbn = :isbn
EOQ;

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':isbn', $message->getIsbn());
        $statement->execute();
    }
}
