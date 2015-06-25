<?php

namespace SWP\Exchange\Subscriber;

use Doctrine\DBAL\Driver\Connection;
use SimpleBus\Message\Message;
use SimpleBus\Message\Subscriber\MessageSubscriber;

class EnrichCatalogInformation implements MessageSubscriber
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
    title = :title
WHERE
    isbn = :isbn
EOQ;

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':title', 'some_title_fetched_elsewhere');
        $statement->bindValue(':isbn', $message->getIsbn());
        $statement->execute();
    }
}
