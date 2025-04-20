<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\Response;

class QuizFailedException extends \Exception
{
    public $data;

    public function __construct(string $message = '', array $data = [], int $statusCode = Response::HTTP_FORBIDDEN)
    {
        parent::__construct($message, $statusCode);
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
