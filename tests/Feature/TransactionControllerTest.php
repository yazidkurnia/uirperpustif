<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Book;
use App\Models\BookStock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_greet_returns_correct_message()
    {
        // Arrange
        $name = 'John';

        // Act
        $response = $this->getJson("/greet/$name");

        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            'message' => "Hello, $name!"
        ]);
    }

    public function test_cancel_peminjaman_transaction_not_found()
    {
        // Arrange
        $request = [
            'id' => Crypt::encryptString(9999) // ID yang tidak ada
        ];

        // Act
        $response = $this->json('DELETE', '/api/cancel-peminjaman', $request);

        // Assert
        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => 'Maaf, transaksi tidak ditemukan.',
        ]);
    }

    public function test_cancel_peminjaman_transaction_approved()
    {
        // Arrange
        $transaction = Transaction::factory()->create(['status_approval' => 'Approved']);
        $request = [
            'id' => Crypt::encryptString($transaction->id)
        ];

        // Act
        $response = $this->json('DELETE', '/cancel-peminjaman', $request);

        // Assert
        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'Maaf, tidak dapat menghapus data, karena transaksi telah disetujui, silahkan hubungi admin.',
        ]);
    }

    public function test_cancel_peminjaman_success()
    {
        // Arrange
        $transaction = Transaction::factory()->create(['status_approval' => 'Pending']);
        TransactionDetail::factory()->create(['transaction_id' => $transaction->id]);
        $request = [
            'id' => Crypt::encryptString($transaction->id)
        ];

        // Act
        $response = $this->json('DELETE', '/api/cancel-peminjaman', $request);

        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Data berhasil dihapus.',
        ]);

        // Verify that the transaction and its details are deleted
        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
        $this->assertDatabaseMissing('transaction_details', ['transaction_id' => $transaction->id]);
    }
}
