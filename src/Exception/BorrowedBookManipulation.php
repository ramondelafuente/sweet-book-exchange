<?php

namespace SWP\Exchange\Exception;

class BorrowedBookManipulation extends BookException
{
    static protected $template = 'Attempted %s of a borrowed book with ID %s';
}