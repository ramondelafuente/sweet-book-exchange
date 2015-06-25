<?php

namespace SWP\Exchange\Command;

use SimpleBus\Message\Message;
use SWP\Exchange\Core\Command;

class BorrowBook implements Command, Message
{
    /**
     * @var string
     */
    private $bookId;

    /**
     * @var string
     */
    private $borrowerId;

    /**
     * @param string $bookId
     * @param string $borrowerId
     */
    public function __construct($bookId, $borrowerId)
    {
        $this->bookId     = $bookId;
        $this->borrowerId = $borrowerId;
    }

    /**
     * @return string
     */
    public function getBookId()
    {
        return $this->bookId;
    }

    /**
     * @return string
     */
    public function getBorrowerId()
    {
        return $this->borrowerId;
    }
}
