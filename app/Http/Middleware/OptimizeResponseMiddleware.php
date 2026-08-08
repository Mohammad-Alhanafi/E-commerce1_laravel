<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OptimizeResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Do not alter binary/streamed responses
        if ($response->headers->has('Content-Disposition')) {
            return $response;
        }

        $contentType = $response->headers->get('Content-Type', '');

        // Apply compression & caching headers for text/html responses
        if (str_contains($contentType, 'text/html') || str_contains($contentType, 'application/json')) {
            // Set performance & browser caching headers
            if (!$request->is('admin*') && $request->isMethod('GET')) {
                $response->headers->set('Cache-Control', 'public, max-age=60, s-maxage=300, must-revalidate');
            }

            // Gzip compression if accepted by client
            if (function_exists('gzencode') && !in_array('ob_gzhandler', ob_list_handlers()) && str_contains($request->header('Accept-Encoding', ''), 'gzip')) {
                $content = $response->getContent();
                if ($content && strlen($content) > 1024) {
                    $compressed = gzencode($content, 6);
                    if ($compressed !== false) {
                        $response->setContent($compressed);
                        $response->headers->set('Content-Encoding', 'gzip');
                        $response->headers->set('Content-Length', (string) strlen($compressed));
                    }
                }
            }
        }

        // Add security & performance hints
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        return $response;
    }
}
