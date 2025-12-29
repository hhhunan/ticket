<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class TicketRateLimitException extends Exception
{
    public function __construct(string $message = "", int $code = 429, ?Throwable $previous = null)
    {
        $message = __('Вы превысили лимит на отправку заявок. Вы можете отправлять не более одной заявки в день.');
        parent::__construct($message, $code, $previous);
    }
}
