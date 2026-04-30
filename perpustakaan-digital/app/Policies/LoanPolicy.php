<?php

namespace App\Policies;

use App\Models\Loan;
use App\Models\User;

class LoanPolicy
{
    /**
     * Determine if the given loan can be viewed by the user.
     */
    public function view(User $user, Loan $loan): bool
    {
        // Admins can view all loans, members can only view their own
        return $user->role === 'admin' || $user->id === $loan->user_id;
    }

    /**
     * Determine if the user can create loans.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the given loan can be updated by the user.
     */
    public function update(User $user, Loan $loan): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the given loan can be deleted by the user.
     */
    public function delete(User $user, Loan $loan): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can return the book.
     */
    public function returnBook(User $user, Loan $loan): bool
    {
        // Admin can always return, member can return their own loan
        return $user->role === 'admin' || ($user->id === $loan->user_id && $loan->status === 'borrowed');
    }
}
