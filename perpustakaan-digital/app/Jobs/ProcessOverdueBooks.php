<?php

namespace App\Jobs;

use App\Models\Loan;
use App\Notifications\OverdueReminderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Carbon\Carbon;

class ProcessOverdueBooks implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Find all overdue loans
        $overdueLoans = Loan::overdue()->get();

        foreach ($overdueLoans as $loan) {
            // Update status to overdue if not already
            if ($loan->status !== 'overdue') {
                $loan->update(['status' => 'overdue']);
            }

            // Send overdue reminder notification
            $daysOverdue = Carbon::today()->diffInDays($loan->due_date);
            
            $loan->user->notify(new OverdueReminderNotification($loan, $daysOverdue));
        }
    }
}
