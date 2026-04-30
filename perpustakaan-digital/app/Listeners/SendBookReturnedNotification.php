<?php

namespace App\Listeners;

use App\Events\BookReturned;
use App\Notifications\BookReturnedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendBookReturnedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(BookReturned $event): void
    {
        $loan = $event->loan;
        
        // Send notification to user
        $loan->user->notify(new BookReturnedNotification($loan));
    }
}
