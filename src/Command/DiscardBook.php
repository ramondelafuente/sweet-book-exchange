<?php

namespace SWP\Exchange\Command;

use SimpleBus\Message\Message;
use SWP\Exchange\Core\Command;

class DiscardBook implements Command, Message
{
    /**
     * @var string
     */
    private $bookId;

    /**
     * @param string $bookId
     */
    public function __construct($bookId)
    {
        $this->bookId = $bookId;
    }

    /**
     * @return string
     */
    public function getBookId()
    {
        return $this->bookId;
    }
}
