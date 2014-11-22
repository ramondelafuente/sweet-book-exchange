<?php

namespace SWP\Exchange\Command;

use SWP\Exchange\Core\Command;

class DiscardBook implements Command
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
