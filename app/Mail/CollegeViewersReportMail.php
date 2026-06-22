<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CollegeViewersReportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public string  $collegeName,
        public array   $stats,
        public array   $viewerRows,
        public ?string $fromDate,
        public ?string $toDate,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Profile Viewers Report – ' . $this->collegeName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.college_viewers_report',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $date     = $this->fromDate ?? now()->toDateString();
        $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $this->collegeName);

        return [
            // ── CSV attachment ──────────────────────────────────────────────
            Attachment::fromData(
                fn () => $this->buildCsv(),
                "{$safeName}_viewers_{$date}.csv"
            )->withMime('text/csv'),

            // ── PDF attachment ──────────────────────────────────────────────
            Attachment::fromData(
                fn () => $this->buildPdf(),
                "{$safeName}_viewers_{$date}.pdf"
            )->withMime('application/pdf'),
        ];
    }

    // ────────────────────────────────────────────────────────────────────────
    // Helpers
    // ────────────────────────────────────────────────────────────────────────

    /**
     * Build a CSV string from $viewerRows.
     */
    private function buildCsv(): string
    {
        $handle = fopen('php://temp', 'r+');

        // Header row
        fputcsv($handle, ['Name', 'Email', 'Phone', 'Viewed At']);

        foreach ($this->viewerRows as $row) {
            fputcsv($handle, [
                $row['name'],
                $row['email'],
                $row['phone'],
                $row['viewed_at'],
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return $csv;
    }

    /**
     * Build a PDF using DomPDF (laravel-dompdf) and return raw bytes.
     *
     * Install once:  composer require barryvdh/laravel-dompdf
     */
    private function buildPdf(): string
    {
        /** @var \Barryvdh\DomPDF\PDF $pdf */
        $pdf = app('dompdf.wrapper');

        $pdf->loadView('emails.college_viewers_report_pdf', [
            'collegeName' => $this->collegeName,
            'stats'       => $this->stats,
            'viewerRows'  => $this->viewerRows,
            'fromDate'    => $this->fromDate,
            'toDate'      => $this->toDate,
        ]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->output();
    }
}