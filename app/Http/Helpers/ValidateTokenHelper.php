<?php

namespace App\Http\Helpers;

use Illuminate\Http\JsonResponse;
use App\Http\Helpers\ResponseFormatterHelper;

class ValidateTokenHelper
{
    public static function validate_token(string $token){
        if (!str_contains($token ,'Bearer')) {
            return ResponseFormatterHelper::error(NULL, 'Token tidak valid', 409);
        }

        return TRUE;
    }
}

