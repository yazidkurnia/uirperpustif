<?php

namespace Tests\Feature\Books;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book; // Pastikan untuk mengimpor model Book

class BookApiControllerTest extends TestCase
{
    use RefreshDatabase; // Menggunakan trait ini untuk mengatur database

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Testing to get five best-selling books.
     */
    public function test_fetch_five_best_books(): void
    {
        // Membuat data dummy buku
        Book::factory()->create(['title' => 'Book A', 'sales' => 100]);
        Book::factory()->create(['title' => 'Book B', 'sales' => 200]);
        Book::factory()->create(['title' => 'Book C', 'sales' => 300]);
        Book::factory()->create(['title' => 'Book D', 'sales' => 400]);
        Book::factory()->create(['title' => 'Book E', 'sales' => 500]);
        Book::factory()->create(['title' => 'Book F', 'sales' => 600]); // Buku tambahan

        // Mengambil 5 buku dengan penjualan terbanyak
        $response = $this->getJson('/api/books/best-sellers'); // Ganti dengan endpoint yang sesuai

        // Memastikan status respons adalah 200
        $response->assertStatus(200);

        // Memastikan kita mendapatkan 5 buku
        $response->assertJsonCount(5);

        // Memastikan buku yang dikembalikan adalah yang dengan penjualan terbanyak
        $response->assertJsonFragment(['title' => 'Book F']);
        $response->assertJsonFragment(['title' => 'Book E']);
        $response->assertJsonFragment(['title' => 'Book D']);
        $response->assertJsonFragment(['title' => 'Book C']);
        $response->assertJsonFragment(['title' => 'Book B']);
    }
}