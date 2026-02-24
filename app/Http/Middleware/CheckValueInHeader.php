<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckValueInHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $number, $some): Response
    {
        if ($request->header('token') !== '12345') {
            return response()->json([
                'message' => 'Unauthorized: Invalid token in header '. $some . ' and number ' . $number,
            ], Response::HTTP_FORBIDDEN);
       
        }
        return $next($request);
    }
}
