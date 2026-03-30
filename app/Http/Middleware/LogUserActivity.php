<?php

namespace App\Http\Middleware;

use App\Services\UserActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogUserActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $user = $request->user();
        if (!$user || !$this->shouldLog($request)) {
            return $response;
        }

        UserActivityLogger::log(
            $this->resolveAction($request),
            $request,
            $user,
            ['status_code' => $response->getStatusCode()]
        );

        return $response;
    }

    private function shouldLog(Request $request): bool
    {
        $method = strtoupper($request->method());

        if (!in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return false;
        }

        $routeName = (string) ($request->route()?->getName() ?? '');

        // Login/logout are tracked by auth event listeners to avoid duplicate entries.
        return !in_array($routeName, ['login', 'logout'], true);
    }

    private function resolveAction(Request $request): string
    {
        $routeName = (string) ($request->route()?->getName() ?? $request->path());
        $friendlyTarget = trim(str_replace(['.', '-'], ' ', $routeName));

        if (!empty($request->allFiles())) {
            return 'Uploaded file via ' . $friendlyTarget;
        }

        return match (strtoupper($request->method())) {
            'POST' => 'Created resource via ' . $friendlyTarget,
            'PUT', 'PATCH' => 'Updated resource via ' . $friendlyTarget,
            'DELETE' => 'Deleted resource via ' . $friendlyTarget,
            default => 'Performed action via ' . $friendlyTarget,
        };
    }
}
