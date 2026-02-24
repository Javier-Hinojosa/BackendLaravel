<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $data = [
            'method' => $request->method(),
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'headers' => $request->headers->all(),
            'body' => $request->getContent(),
        ];

        Log::info('Incoming Request', $data);
    
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        Log::info('Request sended',[
            "status" => $response->getStatusCode(),
            "headers" => $response->headers->all(),
            "content" => $response->getContent(),

        ]);
    }
}
