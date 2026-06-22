<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CollegeView;
use App\Models\College;
use App\Mail\CollegeViewersReportMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
class SendDailyViewersReport extends Command
{
    /**
     * Command name
     */
    protected $signature = 'report:daily-viewers';

    /**
     * Command description
     */
    protected $description = 'Send end-of-day profile viewers report to every college';

    /**
     * Execute the console command
     */
    public function handle()
    {
        Log::info('Daily viewers cron started');
        // Get unique college IDs that received views today
        $collegeIds = CollegeView::whereDate('created_at', today())
            ->pluck('college_id')
            ->unique();

        if ($collegeIds->isEmpty()) {
            $this->info('No views today. No emails sent.');
            return Command::SUCCESS;
        }

        // Fetch colleges
        $colleges = College::whereIn('id', $collegeIds)->get();

        foreach ($colleges as $college) {

            // Get today's viewers
            $viewerRows = CollegeView::with('user')
                ->where('college_id', $college->id)
                ->whereDate('created_at', today())
                ->latest()
                ->get()
                ->map(function ($view) {

                    return [
                        'name'      => optional($view->user)->name ?? '—',
                        'email'     => optional($view->user)->email ?? '',
                        'phone'     => optional($view->user)->phone ?? '',
                        'viewed_at' => $view->created_at->format('d M Y, h:i A'),
                    ];
                })
                ->toArray();

            // Statistics
            $stats = [
                'total' => CollegeView::where('college_id', $college->id)->count(),

                'week' => CollegeView::where('college_id', $college->id)
                    ->whereBetween('created_at', [
                        now()->startOfWeek(),
                        now()->endOfWeek(),
                    ])
                    ->count(),

                'today' => count($viewerRows),

                'recipient' => $college->email,
            ];

            // Send email only if college email exists
            if (!empty($college->email)) {

                Mail::to($college->email)->send(
                    new CollegeViewersReportMail(
                        collegeName: $college->name,
                        stats: $stats,
                        viewerRows: $viewerRows,
                        fromDate: today()->toDateString(),
                        toDate: today()->toDateString(),
                    )
                );

                $this->info(
                    "✅ Sent → {$college->name} ({$college->email}) — {$stats['today']} viewers today"
                );
            }
        }

        $this->info("Done. Reports sent to {$colleges->count()} college(s).");
        Log::info('Daily viewers cron completed');
        return Command::SUCCESS;
    }
}