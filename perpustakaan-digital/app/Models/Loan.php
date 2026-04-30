<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Loan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'book_id',
        'loan_date',
        'due_date',
        'return_date',
        'status',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'loan_date' => 'date',
            'due_date' => 'date',
            'return_date' => 'date',
        ];
    }

    /**
     * Get the user that owns the loan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book that is loaned.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Check if the loan is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->status === 'borrowed' && $this->due_date < Carbon::today();
    }

    /**
     * Scope a query to only include borrowed loans.
     */
    public function scopeBorrowed($query)
    {
        return $query->where('status', 'borrowed');
    }

    /**
     * Scope a query to only include returned loans.
     */
    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    /**
     * Scope a query to only include overdue loans.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'borrowed')
                     ->where('due_date', '<', Carbon::today());
    }

    /**
     * Mark the loan as returned.
     */
    public function markAsReturned(): void
    {
        $this->update([
            'return_date' => Carbon::today(),
            'status' => 'returned',
        ]);
    }
}
