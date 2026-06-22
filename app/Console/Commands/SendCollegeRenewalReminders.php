<?php

namespace App\Console\Commands;

use App\Mail\CollegeRenewalReminderMail;
use App\Models\College;           // ← adjust if your model lives elsewhere
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendCollegeRenewalReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'colleges:send-renewal-reminders';

    /**
     * The console command description.
     */
    protected $description = 'Send renewal reminder emails to colleges whose subscription expires in 2 days';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Target date: exactly 2 days from today (midnight-to-midnight window)
        $targetDate = Carbon::today()->addDays(2);

        $this->info("Checking for colleges expiring on {$targetDate->toDateString()} …");

        // ── FETCH COLLEGES EXPIRING IN 2 DAYS ──────────────────────────
        // created_at + 1 year = expiry date.
        // We want colleges where DATE(created_at + INTERVAL 1 YEAR) = targetDate.
        //
        // Using whereRaw for cross-DB compatibility (MySQL / SQLite / PostgreSQL):
        $colleges = College::whereRaw(
            "DATE(DATE_ADD(created_at, INTERVAL 1 YEAR)) = ?",
            [$targetDate->toDateString()]
        )->get();

        // ── PostgreSQL alternative (uncomment if using Postgres) ────────
        // $colleges = College::whereRaw(
        //     "(created_at + INTERVAL '1 year')::date = ?",
        //     [$targetDate->toDateString()]
        // )->get();

        if ($colleges->isEmpty()) {
            $this->info('No colleges expiring in 2 days. Nothing to do.');
            Log::info('colleges:send-renewal-reminders — no colleges due in 2 days', [
                'checked_date' => $targetDate->toDateString(),
            ]);

            return self::SUCCESS;
        }

        $this->info("Found {$colleges->count()} college(s). Sending reminders …");

        $sent   = 0;
        $failed = 0;

        foreach ($colleges as $college) {
            $expiryDate = Carbon::parse($college->created_at)
                ->addYear()
                ->format('d M Y');

            try {
                Mail::to($college->email)
                    ->send(new CollegeRenewalReminderMail($college, 2, $expiryDate));

                $sent++;

                Log::info('College renewal reminder sent via scheduler', [
                    'college_id' => $college->id,
                    'email'      => $college->email,
                    'expiry'     => $expiryDate,
                ]);

                $this->line("  ✓ Sent to {$college->email} ({$college->name})");

            } catch (\Exception $e) {
                $failed++;

                Log::error('College renewal reminder failed via scheduler', [
                    'college_id' => $college->id,
                    'email'      => $college->email,
                    'error'      => $e->getMessage(),
                ]);

                $this->error("  ✗ Failed for {$college->email}: {$e->getMessage()}");
            }
        }

        $this->info("Done. Sent: {$sent} | Failed: {$failed}");

        return self::SUCCESS;
    }
}
