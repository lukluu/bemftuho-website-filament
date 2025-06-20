<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class VisitStatsChart extends ChartWidget
{

    protected static ?string $heading = 'Statistik Kunjungan Website';
    protected int|string|array $columnSpan = 'full';
    protected static ?string $pollingInterval = null; // Nonaktifkan auto-refresh

    protected function getData(): array
    {
        $filter = request()->get('filter', 'daily'); // 'daily', 'weekly', 'monthly'

        $rawLogs = collect(explode("\n", Storage::get('logs/visitors.log')));
        $logs = $rawLogs->filter()->map(function ($line) {
            [$date, $ip] = explode('|', $line);
            return [
                'date' => $date,
                'ip' => $ip,
            ];
        });

        $grouped = match ($filter) {
            'weekly' => $logs->groupBy(fn($item) => \Carbon\Carbon::parse($item['date'])->startOfWeek()->format('Y-m-d')),
            'monthly' => $logs->groupBy(fn($item) => \Carbon\Carbon::parse($item['date'])->format('Y-m')),
            default => $logs->groupBy('date'),
        };

        $labels = $grouped->keys()->sort()->values();
        $data = $labels->map(fn($key) => $grouped[$key]->pluck('ip')->unique()->count());

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pengunjung Unik',
                    'data' => $data,
                    'fill' => true,
                    'borderColor' => '#3b82f6',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line'; // atau 'line' sesuai preferensi
    }

    protected function getFilters(): ?array
    {
        return [
            'daily' => 'Per Hari',
            'weekly' => 'Per Minggu',
            'monthly' => 'Per Bulan',
        ];
    }
}
