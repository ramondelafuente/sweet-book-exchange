<?php

namespace SWP\Exchange\Command;

use SWP\Exchange\Core\Command;

class AddBook implements Command
{
    /**
     * @var string
     */
    private $isbn;

    /**
     * @var string
     */
    private $ownerId;

    /**
     * @param string $isbn
     * @param string $ownerId
     */
    public function __construct($isbn, $ownerId)
    {
        $this->isbn    = $isbn;
        $this->ownerId = $ownerId;
    }

    /**
     * @return string
     */
    public function getISBN()
    {
        return $this->isbn;
    }

    /**
     * @return string
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }
}
