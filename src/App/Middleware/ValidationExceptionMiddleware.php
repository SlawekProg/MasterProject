<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\Exceptions\ValidationExeption;

class ValidationExceptionMiddleware implements MiddlewareInterface
{
    public function process(callable $next)
    {
        try {
            $next();
        } catch (ValidationExeption $e) {
            $oldFormData = $_POST;

            $excludedFields = ['password', 'confirmPassword'];
            $formattedFormData = array_diff_key(
                $oldFormData,
                array_flip($excludedFields)
            );

            $_SESSION['errors'] = $e->errors;
            $_SESSION['oldFormData'] = $_POST;
            //Przechowuje strone formularza w którym nacisnięto submit
            $refer = $_SERVER['HTTP_REFERER'];
            redirectTo($refer);
        }
    }
}
