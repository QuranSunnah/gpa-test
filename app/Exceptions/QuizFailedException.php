<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\Response;

class QuizFailedException extends \Exception
{
    public function __construct(
        string $message = '',
        public array $data = [],
        int $statusCode = Response::HTTP_FORBIDDEN,
    ) {
        parent::__construct($message, $statusCode);
    }

    public function getData(): array
    {
        return $this->data;
    }
}
