<?php

namespace App\Exceptions;

use Exception;

class InsufficientLeaveBalanceException extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->message,
            ], 422);
        }

        return back()->withErrors([
            'leave_type_id' => $this->message,
        ])->withInput();
    }
}
