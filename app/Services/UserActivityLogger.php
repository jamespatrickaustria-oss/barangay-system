<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class UserActivityLogger
{
    public static function log(string $action, ?Request $request = null, ?User $user = null, array $extra = []): void
    {
        try {
            /** @var User|null $resolvedUser */
            $resolvedUser = $user ?? auth()->user();

            $role = $resolvedUser?->role ? Str::title($resolvedUser->role) : 'Guest';
            $userName = $resolvedUser
                ? (method_exists($resolvedUser, 'getFullName') ? $resolvedUser->getFullName() : ($resolvedUser->name ?? 'Unknown User'))
                : 'Guest';
            $userLabel = $resolvedUser ? sprintf('%s (ID: %d)', $userName, $resolvedUser->id) : 'Guest';

            $currentRequest = $request ?? request();

            $message = sprintf(
                'Role: %s | User: %s | Action: %s',
                $role,
                $userLabel,
                $action
            );

            $context = array_filter([
                'role' => $role,
                'user_id' => $resolvedUser?->id,
                'user_name' => $userName,
                'ip' => $currentRequest?->ip(),
                'user_agent' => $currentRequest?->userAgent(),
                'method' => $currentRequest?->method(),
                'url' => $currentRequest?->fullUrl(),
                'route' => $currentRequest?->route()?->getName(),
                'extra' => $extra,
            ], static fn ($value): bool => $value !== null && $value !== '');

            Log::channel('user_logs')->info($message, $context);
            Log::channel(self::resolveRoleChannel($resolvedUser?->role))->info($message, $context);
        } catch (Throwable $exception) {
            // Never block user requests when activity logging fails.
        }
    }

    private static function resolveRoleChannel(?string $role): string
    {
        return match (strtolower((string) $role)) {
            'admin' => 'admin_logs',
            'official' => 'official_logs',
            'resident' => 'resident_logs',
            default => 'guest_logs',
        };
    }
}
