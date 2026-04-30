<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OverdueReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Loan $loan,
        protected int $daysOverdue
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
            ->subject('Overdue Book Reminder - Immediate Action Required')
            ->greeting('Dear ' . $notifiable->name . ',')
            ->error()
            ->line('This is a reminder that you have an overdue book:')
            ->line('**Title:** ' . $this->loan->book->title)
            ->line('**Author:** ' . $this->loan->book->author)
            ->line('**Due Date:** ' . $this->loan->due_date->format('d M Y'))
            ->line('**Days Overdue:** ' . $this->daysOverdue . ' days')
            ->line('Please return the book as soon as possible to avoid further penalties.')
            ->action('Return Book Now', url('/loans/' . $this->loan->id))
            ->line('If you have already returned this book, please ignore this message.');
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
            'days_overdue' => $this->daysOverdue,
            'message' => 'Book "' . $this->loan->book->title . '" is ' . $this->daysOverdue . ' days overdue!',
        ];
    }
}
