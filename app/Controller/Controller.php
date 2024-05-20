<?php

declare(strict_types=1);

namespace App\Controller;

class Controller
{
    protected function error($code, $message)
    {
        http_response_code((int) $code);

        return json_encode([
            'status' => $code,
            'error' => $message,
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
