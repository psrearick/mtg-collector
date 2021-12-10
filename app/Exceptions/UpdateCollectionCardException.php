<?php

namespace App\Exceptions;

use Exception;

class UpdateCollectionCardException extends Exception
{
    public function __construct(string $message = "Failed to update collection card")
    {
        parent::__construct($message);
    }
}