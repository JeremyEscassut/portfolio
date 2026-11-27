<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Security headers added to every web response (registered in bootstrap/app.php).
 *
 * - X-Content-Type-Options: the browser must not guess a file type (a PDF or an
 *   image can never be run as a script).
 * - Referrer-Policy: links to other sites only send the domain, not the full URL.
 * - X-Frame-Options + CSP frame-ancestors: the site cannot be shown inside another
 *   site's frame (clickjacking).
 * - Permissions-Policy: camera, microphone and location are never needed here.
 * - Strict-Transport-Security (HTTPS only): the browser keeps using HTTPS for a year.
 *   No includeSubDomains, so other subdomains of the domain are not affected.
 *
 * A full Content-Security-Policy is not set yet: the inline scripts of the layout
 * (theme, consent, Google Analytics) must first move to files (see AUDIT_2026-11-27, S-5).
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $headers = [
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'X-Frame-Options' => 'SAMEORIGIN',
            'Content-Security-Policy' => "frame-ancestors 'self'",
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=()',
        ];

        if ($request->isSecure()) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000';
        }

        foreach ($headers as $name => $value) {
            if (! $response->headers->has($name)) {
                $response->headers->set($name, $value);
            }
        }

        return $response;
    }
}
