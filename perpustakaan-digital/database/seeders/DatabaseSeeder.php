<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@library.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Library Admin Office',
        ]);

        // Create member users
        $members = User::createMany([
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'role' => 'member',
                'phone' => '081234567891',
                'address' => 'Jakarta',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'role' => 'member',
                'phone' => '081234567892',
                'address' => 'Bandung',
            ],
        ]);

        // Create categories
        $categories = [
            ['name' => 'Fiction', 'slug' => 'fiction', 'description' => 'Fiction books and novels'],
            ['name' => 'Science', 'slug' => 'science', 'description' => 'Science and technology books'],
            ['name' => 'History', 'slug' => 'history', 'description' => 'Historical books and documents'],
            ['name' => 'Biography', 'slug' => 'biography', 'description' => 'Biographies and autobiographies'],
            ['name' => 'Children', 'slug' => 'children', 'description' => 'Books for children'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create sample books
        $books = [
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'isbn' => '978-0-06-112008-4',
                'publisher' => 'Harper Perennial',
                'published_year' => 1960,
                'category_id' => 1,
                'stock' => 5,
                'available_stock' => 5,
                'description' => 'A classic novel about racial injustice in the American South.',
            ],
            [
                'title' => 'A Brief History of Time',
                'author' => 'Stephen Hawking',
                'isbn' => '978-0-553-38016-3',
                'publisher' => 'Bantam Books',
                'published_year' => 1988,
                'category_id' => 2,
                'stock' => 3,
                'available_stock' => 3,
                'description' => 'A landmark volume in science writing about cosmology.',
            ],
            [
                'title' => 'Sapiens: A Brief History of Humankind',
                'author' => 'Yuval Noah Harari',
                'isbn' => '978-0-06-231609-7',
                'publisher' => 'Harper',
                'published_year' => 2015,
                'category_id' => 3,
                'stock' => 4,
                'available_stock' => 4,
                'description' => 'A narrative history of humanity.',
            ],
            [
                'title' => 'Steve Jobs',
                'author' => 'Walter Isaacson',
                'isbn' => '978-1-4516-4853-9',
                'publisher' => 'Simon & Schuster',
                'published_year' => 2011,
                'category_id' => 4,
                'stock' => 2,
                'available_stock' => 2,
                'description' => 'The exclusive biography of Steve Jobs.',
            ],
            [
                'title' => 'Charlotte\'s Web',
                'author' => 'E.B. White',
                'isbn' => '978-0-06-440055-8',
                'publisher' => 'Harper Collins',
                'published_year' => 1952,
                'category_id' => 5,
                'stock' => 6,
                'available_stock' => 6,
                'description' => 'A beloved children\'s classic about friendship.',
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }

        // Create some loans
        Loan::create([
            'user_id' => 2,
            'book_id' => 1,
            'loan_date' => now()->subDays(10),
            'due_date' => now()->addDays(4),
            'status' => 'borrowed',
            'notes' => 'First time borrower',
        ]);

        // Update book stock
        Book::find(1)->decrement('available_stock');
    }
}
