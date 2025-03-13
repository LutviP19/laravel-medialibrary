<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if ($request->is('api/*')) {
            return response()->json([
                'message' => 'Server is running.',
                'statusCode' => 200
            ], 200);
        }

        return 'Server is running.';
    }
}
