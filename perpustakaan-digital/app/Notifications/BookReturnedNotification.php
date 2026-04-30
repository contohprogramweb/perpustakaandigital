<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookReturnedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Loan $loan
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Book Returned Successfully')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have successfully returned the following book:')
            ->line('**Title:** ' . $this->loan->book->title)
            ->line('**Author:** ' . $this->loan->book->author)
            ->line('**Return Date:** ' . $this->loan->return_date->format('d M Y'))
            ->line('Thank you for returning the book on time!')
            ->action('Browse More Books', url('/books'))
            ->line('We hope to see you again soon!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'loan_id' => $this->loan->id,
            'book_title' => $this->loan->book->title,
            'return_date' => $this->loan->return_date->format('Y-m-d'),
            'message' => 'You returned "' . $this->loan->book->title . '" on ' . $this->loan->return_date->format('d M Y'),
        ];
    }
}
