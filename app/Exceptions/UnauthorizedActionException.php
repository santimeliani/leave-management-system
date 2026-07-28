<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedActionException extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses untuk melakukan aksi ini.',
            ], 403);
        }

        abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
    }
}
