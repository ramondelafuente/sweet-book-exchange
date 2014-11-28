<?php

namespace SWP\Exchange\Exception;

class DiscardedBookManipulation extends BookException
{
    static protected $template = 'Attempted %s of a discarded book with ID %s';
}