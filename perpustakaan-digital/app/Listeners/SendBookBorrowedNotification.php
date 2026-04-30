<?php

namespace App\Listeners;

use App\Events\BookBorrowed;
use App\Notifications\BookBorrowedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendBookBorrowedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(BookBorrowed $event): void
    {
        $loan = $event->loan;
        
        // Send notification to user
        $loan->user->notify(new BookBorrowedNotification($loan));
    }
}
