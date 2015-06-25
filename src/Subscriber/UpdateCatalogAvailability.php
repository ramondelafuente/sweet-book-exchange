<?php

namespace SWP\Exchange\Subscriber;

use Doctrine\DBAL\Connection;
use SimpleBus\Message\Message;
use SimpleBus\Message\Subscriber\MessageSubscriber;

final class UpdateCatalogAvailability implements MessageSubscriber
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

        if ($message instanceof \SWP\Exchange\Event\BookWasBorrowed) {
            $statement = $this->prepareDeduction();
        } elseif ($message instanceof \SWP\Exchange\Event\BookWasReturned) {
            $statement = $this->prepareAddition();
        } else {
            throw new \InvalidArgumentException('Wrong type of Message sent to ' . __CLASS__  . ': ' . get_class($message));
        }
        $statement->bindValue(':isbn', $message->getIsbn());
        $statement->execute();
    }

    private function prepareAddition()
    {
        $sql = <<< EOQ
UPDATE
    catalog
SET
    available_copies = available_copies+1
WHERE
    isbn = :isbn
EOQ;

        return $this->connection->prepare($sql);
    }

    private function prepareDeduction()
    {
        $sql = <<< EOQ
UPDATE
    catalog
SET
    available_copies = available_copies-1
WHERE
    isbn = :isbn
EOQ;

        return $this->connection->prepare($sql);
    }
}
