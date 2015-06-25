<?php

namespace SWP\Exchange\Subscriber;

use Doctrine\DBAL\Connection;
use SimpleBus\Message\Message;
use SimpleBus\Message\Subscriber\MessageSubscriber;

class AddBookToCatalog implements MessageSubscriber
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
INSERT INTO
    catalog
SET
    isbn = :isbn,
    available_copies=1
ON DUPLICATE KEY UPDATE
    available_copies = available_copies+1
EOQ;

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':isbn', $message->getIsbn());
        $statement->execute();
    }
}
