<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use App\Exceptions\SessionException;

class SessionMiddleware implements MiddlewareInterface
{
    public function process(callable $next)
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            throw new SessionException("Session alredy active.");
        }

        if (headers_sent($fileName, $line)) {
            throw new SessionException("Headers alredy sent.
            Consider enabling output buffering. Data outputted from {$fileName}
             - Line: {$line}");
        }
        session_start();

        $next();

        session_write_close();
    }
}
