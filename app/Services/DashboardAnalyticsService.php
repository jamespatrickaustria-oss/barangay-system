<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardAnalyticsService
{
    public function getAdminChartData(): array
    {
        return [
            'monthlyRegistrations' => $this->getMonthlyResidentRegistrations(),
            'weeklyRegistrations' => $this->getWeeklyResidentRegistrations(),
            'residentStatusDistribution' => $this->getResidentStatusDistribution(),
            'roleDistribution' => $this->getRoleDistribution(),
            'generatedAt' => now()->toIso8601String(),
        ];
    }

    public function getOfficialChartData(int $officialId): array
    {
        return [
            'monthlyRegistrations' => $this->getMonthlyResidentRegistrations(),
            'weeklyRegistrations' => $this->getWeeklyResidentRegistrations(),
            'residentStatusDistribution' => $this->getResidentStatusDistribution(),
            'approvalActivity' => $this->getOfficialApprovalActivity($officialId),
            'generatedAt' => now()->toIso8601String(),
        ];
    }

    private function getMonthlyResidentRegistrations(int $months = 6): array
    {
        $start = now()->startOfMonth()->subMonths($months - 1);

        $raw = User::query()
            ->where('role', 'resident')
            ->where('created_at', '>=', $start)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as period, COUNT(*) as total")
            ->groupBy('period')
            ->orderBy('period')
            ->pluck('total', 'period');

        $labels = [];
        $values = [];

        for ($i = 0; $i < $months; $i++) {
            $current = $start->copy()->addMonths($i);
            $key = $current->format('Y-m');

            $labels[] = $current->format('M Y');
            $values[] = (int) ($raw[$key] ?? 0);
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    private function getWeeklyResidentRegistrations(int $days = 7): array
    {
        $start = now()->startOfDay()->subDays($days - 1);

        $raw = User::query()
            ->where('role', 'resident')
            ->where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        $labels = [];
        $values = [];

        for ($i = 0; $i < $days; $i++) {
            $current = $start->copy()->addDays($i);
            $key = $current->toDateString();

            $labels[] = $current->format('D');
            $values[] = (int) ($raw[$key] ?? 0);
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    private function getResidentStatusDistribution(): array
    {
        $statuses = ['pending', 'approved', 'rejected'];

        $raw = User::query()
            ->where('role', 'resident')
            ->whereIn('status', $statuses)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'labels' => ['Pending', 'Approved', 'Rejected'],
            'values' => [
                (int) ($raw['pending'] ?? 0),
                (int) ($raw['approved'] ?? 0),
                (int) ($raw['rejected'] ?? 0),
            ],
        ];
    }

    private function getRoleDistribution(): array
    {
        $roles = ['resident', 'official', 'admin'];

        $raw = User::query()
            ->whereIn('role', $roles)
            ->select('role', DB::raw('COUNT(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role');

        return [
            'labels' => ['Residents', 'Officials', 'Admins'],
            'values' => [
                (int) ($raw['resident'] ?? 0),
                (int) ($raw['official'] ?? 0),
                (int) ($raw['admin'] ?? 0),
            ],
        ];
    }

    private function getOfficialApprovalActivity(int $officialId, int $days = 7): array
    {
        $start = now()->startOfDay()->subDays($days - 1);

        $raw = User::query()
            ->where('role', 'resident')
            ->whereIn('status', ['approved', 'rejected'])
            ->where('approved_by', $officialId)
            ->whereNotNull('approved_at')
            ->where('approved_at', '>=', $start)
            ->selectRaw('DATE(approved_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        $labels = [];
        $values = [];

        for ($i = 0; $i < $days; $i++) {
            $current = $start->copy()->addDays($i);
            $key = $current->toDateString();

            $labels[] = $current->format('D');
            $values[] = (int) ($raw[$key] ?? 0);
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }
}