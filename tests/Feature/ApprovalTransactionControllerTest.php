<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApprovalTransactionControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    // test case untuk melakukan pengujian approval peminjaman oterhadap id yang salah
    public function test_approve_transaksi_invalid_id()
    {
        // Arrange
        $request = [
            'id' => Crypt::encryptString(9999), // ID yang tidak ada
            'status_approval' => 'Approved'
        ];

        // Act
        $response = $this->json('POST', '/approve-transaksi-peminjaman', $request);

        // Assert
        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => 'Gagal, terjadi kesalahan pada data transaksi',
        ]);
    }
}
