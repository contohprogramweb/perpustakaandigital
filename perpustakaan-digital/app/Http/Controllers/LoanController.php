<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoanController extends Controller
{
    /**
     * Display a listing of loans.
     */
    public function index()
    {
        $loans = Loan::with(['user', 'book'])
            ->latest()
            ->paginate(15);
        
        return view('loans.index', compact('loans'));
    }

    /**
     * Show the form for creating a new loan.
     */
    public function create()
    {
        $users = User::where('role', 'member')->get();
        $books = Book::available()->get();
        
        return view('loans.create', compact('users', 'books'));
    }

    /**
     * Store a newly created loan in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'loan_date' => 'required|date',
            'due_date' => 'required|date|after:loan_date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $book = Book::findOrFail($validated['book_id']);
        
        if (!$book->isAvailable()) {
            return back()->withErrors(['book_id' => 'Book is not available for loan.']);
        }

        // Check if user already has this book borrowed
        $existingLoan = Loan::where('user_id', $validated['user_id'])
            ->where('book_id', $validated['book_id'])
            ->where('status', 'borrowed')
            ->exists();

        if ($existingLoan) {
            return back()->withErrors(['book_id' => 'User already has this book borrowed.']);
        }

        // Create loan
        Loan::create($validated);

        // Decrease available stock
        $book->decrement('available_stock');

        return redirect()->route('loans.index')
            ->with('success', 'Loan created successfully.');
    }

    /**
     * Display the specified loan.
     */
    public function show(Loan $loan)
    {
        $loan->load(['user', 'book.category']);
        return view('loans.show', compact('loan'));
    }

    /**
     * Process book return.
     */
    public function returnBook(Loan $loan)
    {
        if ($loan->status === 'returned') {
            return back()->withErrors(['error' => 'This book has already been returned.']);
        }

        $loan->markAsReturned();
        
        // Increase available stock
        $loan->book->increment('available_stock');

        return redirect()->route('loans.show', $loan)
            ->with('success', 'Book returned successfully.');
    }

    /**
     * Show overdue loans.
     */
    public function overdue()
    {
        $loans = Loan::overdue()
            ->with(['user', 'book'])
            ->latest('due_date')
            ->paginate(15);
        
        return view('loans.overdue', compact('loans'));
    }

    /**
     * Remove the specified loan from storage.
     */
    public function destroy(Loan $loan)
    {
        if ($loan->status === 'borrowed') {
            // If book hasn't been returned, increase stock
            $loan->book->increment('available_stock');
        }

        $loan->delete();

        return redirect()->route('loans.index')
            ->with('success', 'Loan record deleted successfully.');
    }
}
