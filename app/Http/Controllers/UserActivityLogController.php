<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class UserActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $roleFilter = strtolower(trim((string) $request->query('role', '')));
        $dateFilter = trim((string) $request->query('date', ''));
        $logPath = storage_path('logs/user_logs.log');

        $entries = [];
        if (is_file($logPath) && is_readable($logPath)) {
            $lines = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];

            foreach (array_reverse($lines) as $line) {
                $entry = $this->parseLogLine($line);
                if ($entry === null) {
                    continue;
                }

                if ($roleFilter !== '' && strtolower($entry['role']) !== $roleFilter) {
                    continue;
                }

                if ($dateFilter !== '' && substr($entry['timestamp'], 0, 10) !== $dateFilter) {
                    continue;
                }

                $entries[] = $entry;

                // Keep page responsive for very large log files.
                if (count($entries) >= 500) {
                    break;
                }
            }
        }

        return view('admin.user-logs.index', [
            'entries' => $entries,
            'roleFilter' => $roleFilter,
            'dateFilter' => $dateFilter,
        ]);
    }

    private function parseLogLine(string $line): ?array
    {
        if (!preg_match('/^\[(?<timestamp>[^\]]+)\]\s+[^:]+:\s+(?<payload>.+)$/', $line, $matches)) {
            return null;
        }

        $payload = trim($matches['payload']);
        if (!str_starts_with($payload, 'Role: ')) {
            return null;
        }

        $parts = array_map('trim', explode('|', $payload));
        if (count($parts) < 3) {
            return null;
        }

        $role = trim(str_replace('Role:', '', $parts[0]));
        $user = trim(str_replace('User:', '', $parts[1]));

        $actionPart = trim(str_replace('Action:', '', $parts[2]));
        for ($i = 3; $i < count($parts); $i++) {
            $actionPart .= ' | ' . $parts[$i];
        }

        $actionPart = preg_replace('/\s+\{.*\}$/', '', $actionPart) ?? $actionPart;
        $actionPart = preg_replace('/\s+\[\]$/', '', $actionPart) ?? $actionPart;

        return [
            'timestamp' => $matches['timestamp'],
            'role' => $role,
            'user' => $user,
            'action' => $actionPart,
        ];
    }
}
