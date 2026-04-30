<?php

namespace App\Console\Commands;

use App\Jobs\ProcessOverdueBooks;
use Illuminate\Console\Command;

class CheckOverdueBooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-overdue-books';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for overdue books and send notifications';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Checking for overdue books...');
        
        ProcessOverdueBooks::dispatch();
        
        $this->info('Overdue book check completed. Notifications queued.');
    }
}
