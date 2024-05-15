<?php

declare(strict_types=1);

namespace App\Controller;

class Controller
{
    protected function error(int $code, string $message)
    {
        http_response_code($code);

        return json_encode([
            'status' => $code,
            'error' => json_decode($message),
        ], JSON_PRETTY_PRINT);
    }

    protected function notFound()
    {
        http_response_code(404);

        return false;
    }

    protected function internalServerError()
    {
        http_response_code(500);

        return false;
    }
}
