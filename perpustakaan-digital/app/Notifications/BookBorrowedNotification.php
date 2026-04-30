<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookBorrowedNotification extends Notification implements ShouldQueue
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
            ->subject('Book Borrowed Successfully')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have successfully borrowed the following book:')
            ->line('**Title:** ' . $this->loan->book->title)
            ->line('**Author:** ' . $this->loan->book->author)
            ->line('**Borrow Date:** ' . $this->loan->loan_date->format('d M Y'))
            ->line('**Due Date:** ' . $this->loan->due_date->format('d M Y'))
            ->line('Please return the book before the due date to avoid penalties.')
            ->action('View My Loans', url('/loans'))
            ->line('Thank you for using our library service!');
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
            'due_date' => $this->loan->due_date->format('Y-m-d'),
            'message' => 'You borrowed "' . $this->loan->book->title . '". Due on ' . $this->loan->due_date->format('d M Y'),
        ];
    }
}
