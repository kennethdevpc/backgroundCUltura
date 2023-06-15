<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Closure;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof BinaryFileResponse) {
            $response->headers->set('Access-Control-Allow-Origin', 'https://www.ejemplo.com');
            $response->headers->set('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
        } else {
            $response->header('Access-Control-Allow-Origin', 'https://www.ejemplo.com');
            $response->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
        }

        return $response;
    }
}
