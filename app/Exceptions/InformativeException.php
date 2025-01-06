<?php

namespace App\Exceptions;

use Exception;

class InformativeException extends Exception
{
    protected $data;

    /**
     * Create a new InformativeException instance.
     *
     * @param string $message
     * @param array $data
     * @param int $code
     */
    public function __construct(string $message, array $data = [], int $code = 200)
    {
        parent::__construct($message, $code);
        $this->data = $data;
    }

    /**
     * Get additional data associated with the exception.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
