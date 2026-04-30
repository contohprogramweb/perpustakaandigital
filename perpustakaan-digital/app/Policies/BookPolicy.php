<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    /**
     * Determine if the given book can be viewed by the user.
     */
    public function view(?User $user, Book $book): bool
    {
        // Anyone can view books
        return true;
    }

    /**
     * Determine if the user can create books.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the given book can be updated by the user.
     */
    public function update(User $user, Book $book): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the given book can be deleted by the user.
     */
    public function delete(User $user, Book $book): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can borrow the book.
     */
    public function borrow(User $user, Book $book): bool
    {
        return $user->role === 'member' && $book->isAvailable();
    }
}
