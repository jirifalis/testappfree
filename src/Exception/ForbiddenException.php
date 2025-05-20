<?php declare(strict_types=1);

namespace App\Exception;

use Exception;

class ForbiddenException extends Exception
{
    private const string MESSAGE = 'Forbidden access to resource: %s';

    public function __construct(string $resource)
    {
        $message = sprintf(self::MESSAGE, $resource);
        parent::__construct($message, 403);
    }
}