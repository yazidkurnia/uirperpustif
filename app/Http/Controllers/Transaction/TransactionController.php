<?php

namespace App\Http\Controllers\Transaction;

use TCPDF;
use DateTime;
use App\Models\Book\Book;
use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Label\Label;
use Illuminate\Support\Facades\DB;
use App\Models\BookStock\BookStock;
use App\Http\Controllers\Controller;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Auth;
use Endroid\QrCode\Encoding\Encoding;
use Illuminate\Support\Facades\Crypt;
use Endroid\QrCode\RoundBlockSizeMode;
use App\Http\Helpers\QrGeneratorHelper;
use App\Models\Transaction\Transaction;
use Endroid\QrCode\ErrorCorrectionLevel;
use App\Models\TransactionDetail\TransactionDetail;

class TransactionController extends Controller
{
    protected QrGeneratorHelper $qrCodeHelper;

    public function __construct(QrGeneratorHelper $qrCodeHelper)
    {
        $this->qrCodeHelper = $qrCodeHelper;
    }
    /**
     * pada fungsi berikut menampilkan halaman peminjaman dari sisi user
     * dapat diakase oleh semua role user
     */
    public function index(Request $request){
        $dataBuku = Book::select(
            'books.id as book_id', 
            'book_stocks.id as stock_id', 
            'books.judul',
            'books.penulis',
            'books.tahun_terbit',
            'books.penerbit')
            ->join('book_stocks', 'book_stocks.category_id', '=', 'books.category_id')->get();
        foreach ($dataBuku as $list) {
            $list->book_id = Crypt::encryptString($list->book_id);
            $list->stock_id = Crypt::encryptString($list->stock_id);
        }

        $data['total_buku_keseluruhan'] = count(Book::all());

        $data['title'] = 'Daftar Buku';
        $data['book_list'] = $dataBuku;

        // hitung total peminjaman waiting, approved, rejected
        $data['total_peminjaman_pending']  = count(Transaction::where('status_approval', 'Waiting')->where('jenis_transaksi', 'Peminjaman')->get());
        $data['total_peminjaman_approved'] = count(Transaction::where('status_approval', 'Approved')->where('jenis_transaksi', 'Peminjaman')->get());

        $data['total_pengembalian_pending']  = count(Transaction::where('status_approval', 'Waiting')->where('jenis_transaksi', 'Pengembalian')->get());
        $data['total_pengembalian_approved'] = count(Transaction::where('status_approval', 'Approved')->where('jenis_transaksi', 'Pengembalian')->get());
        
        # lakukan perhitunggan untuk mendapatkan data peminjaman buku perbulanannya
        $getAllPeminjaman = Transaction::where('jenis_transaksi', 'Peminjaman')->get();
        $getAllPengembalian = Transaction::where('jenis_transaksi', 'Pengembalian')->get();

        $chartData = [
            'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0,
            'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0,
            'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
        ];

        $chartDataPengembalian = [
            'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0,
            'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0,
            'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
        ];
        
        foreach ($getAllPeminjaman as $list) {
            $month = date('M', strtotime($list->tgl_pinjam));
            if (array_key_exists($month, $chartData)) {
                $chartData[$month]++;
            }
        }
        foreach ($getAllPengembalian as $list) {
            $month = date('M', strtotime($list->tgl_wajib_kembali));
            if (array_key_exists($month, $chartDataPengembalian)) {
                $chartDataPengembalian[$month]++;
            }
        }
        // Jika Anda ingin mengubah formatnya menjadi array yang lebih mudah digunakan untuk chart
        // Jika Anda ingin mengubah formatnya menjadi array yang lebih mudah digunakan untuk chart
        $formattedChartData = [
            'labels' => array_keys($chartData), // Nama bulan
            'data' => array_values($chartData), // Jumlah peminjaman per bulan
            'data_pengembalian' => array_values($chartDataPengembalian)
        ];

        // Pastikan tidak ada nilai null dalam data
        foreach ($formattedChartData['data'] as $key => $value) {
            if ($value === null) {
                $formattedChartData['data'][$key] = 0; // Ubah null menjadi 0
            }
        }
        
        $data['formattedChartData'] = $formattedChartData;

        return view('pages.peminjaman.index', $data);
    }

    /**
     * pada fungsi ini akan menampilkan data buku berdasarkan id dari buku yang dipilih
     * @param id string (id buku dari table books)
     */                                                                                                                     
    public function pengajuan_peminjaman($bookId){
        $validBookId = $bookId != '' ? is_int((int)Crypt::decryptString($bookId)) ? (int)Crypt::decryptString($bookId) != 0 ? (int)Crypt::decryptString($bookId) : NULL  : NULL : NULL;
    
        if ($validBookId == NULL) {
            return redirect()->back()->withErrors('Data buku tidak valid')->withInput();
        }
    
        $dataBuku = Book::find($validBookId);
        $data['id_buku'] = $bookId;
        $data['detail_buku'] = $dataBuku;
        $data['title'] = 'Proses Peminjaman';
    
        // Encrypt the IDs and create a new array
        $dataOpsiBuku = Book::get()->map(function($list) {
            return [
                'id' => Crypt::encryptString($list->id),
                'judul' => $list->judul,
                'no_revisi' => $list->no_revisi,
                'penulis' => $list->penulis,
                'tahun_terbit' => $list->tahun_terbit,
                'penerbit' => $list->penerbit,
                'created_at' => $list->created_at,
                'updated_at' => $list->updated_at,
                'image_url' => $list->image_url,
            ];
        });
    
        $data['books'] = $dataOpsiBuku;
    
        return view('pages.peminjaman.view_detail_book', $data);
    }

    public function store_data_peminjaman(Request $request){
        $bookId          = $request->book_id != '' ? is_int((int)Crypt::decryptString($request->book_id)) ? (int)Crypt::decryptString($request->book_id) != 0 ? (int)Crypt::decryptString($request->book_id) : NULL : NULL : NULL;
        $tglPeminjaman   = $request->tanggal_pinjam;
        $additionalBooks  = [];

        if (isset($request->addional_books)) {
            $additionalBooks = $request->addional_books;
        }
        // Initialize an array to hold the decrypted values
        $decryptedBooks = [];

        if (!empty($additionalBooks)) {
            foreach ($additionalBooks as $book) {
                try {
                    // Decrypt the book ID
                    $decryptedBookId = Crypt::decryptString($book);
                    $decryptedBooks[] = $decryptedBookId; // Store the decrypted ID
                } catch (DecryptException $e) {
                    // Handle the exception if decryption fails
                    return redirect()->back()->withErrors('Failed to decrypt book ID: ' . $e->getMessage());
                }
            }
        }
        if(Auth::user()->roleid != 1)
        {
            if((count($decryptedBooks) + 1) > 2){
                return response()->json([
                    'success' => FALSE,
                    'message' => 'Terjadi kesalahan, ketentuan hanya boleh meminjam 2 buku perjudul, total buku yang anda pinjam adalah ' . (count($decryptedBooks) + (int)$bookId),
                    'data'    => [],
                ], 500);        
            }
        }

        if (in_array($bookId, $decryptedBooks)) {
            $duplikatBook = Book::find($bookId);
            return response()->json([
                'success' => FALSE,
                'message' => 'Terjadi kesalahan, buku dengan judul ' . $duplikatBook->judul . ' telah dipilih lebih dari satu kali, ketentuan hanya boleh meminjam 1 buku perjudul!.',
                'data'    => [],
            ], 400);
        }
        
        if(!$request->tanggal_pinjam){
            return response()->json([
                'success' => FALSE,
                'message' => 'Terjadi kesalahan, Tanggal peminjaman tidak boleh kosong!.',
                'data'    => [],
            ], 400);
        }

        // Membuat objek DateTime dari tanggal yang diberikan
        $tglPengembalian = new DateTime($tglPeminjaman);

        // Menambahkan 5 hari
        $tglPengembalian->modify('+7 days');

        if ($bookId == NULL){
            return response()->json([
                'success' => FALSE,
                'message' => 'Terjadi kesalahan, pada data buku!.',
                'data'    => [],
            ], 500);
        }
        
        DB::beginTransaction();
    
        try {

            $transaction = Transaction::create([
                'userid'            => Auth::user()->id,
                'jenis_transaksi'   => 'Peminjaman',
                'tgl_pinjam'        => $request->tanggal_pinjam,
                'tgl_wajib_kembali' => $tglPengembalian,
                'status_approval'   => 'Waiting'
            ]);

            $encryptedTransactionId = Crypt::encryptString($transaction->id);

            $style = array(
                'border' => false, // No border
                'vpadding' => 0,   // Vertical padding
                'hpadding' => 0,   // Horizontal padding
                'fgcolor' => array(0, 0, 0), // Foreground color (black)
                'bgcolor' => array(255, 255, 255), // Background color (white)
                'position' => 'S', // Positioning
            );

            // Generate QR code
            $qrCodeData = "http://192.168.248.109:8000/detail-peminjaman/{$encryptedTransactionId}"; // Data to encode in QR code
            $pdf = new TCPDF();
            $pdf->AddPage();
    
            // Add title to the PDF
            $pdf->SetFont('helvetica', 'B', 16);
            $pdf->Cell(0, 10, 'Perpustakaan Digital Universitas Islam Riau', 0, 1, 'C'); // Centered title
            $pdf->Cell(0, 10, 'Program Studi Teknik Informatika', 0, 1, 'C'); // Centered subtitle
            $pdf->Ln(10); // Add a line break
    
            // Add QR code at the center of the page
            $pdf->write2DBarcode($qrCodeData, 'QRCODE,H', 75, 50, 50, 50, [], 'N'); // Centered QR code
    
            // Define the path to save the PDF
            $pdfFilePath = public_path('qrcodes/transaction_' . $transaction->id . '.pdf');
            $pdf->Output($pdfFilePath, 'F'); // Save the PDF file
    
            // Store the URL of the saved PDF in the transaction
            $transaction->qr_url = 'qrcodes/transaction_' . $transaction->id . '.pdf';
            $transaction->save();

            if (!empty($additionalBooks)) {
                // additional book
                for($i = 0; $i < count($decryptedBooks); $i++){
                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'book_id'        => $decryptedBooks[$i]
                    ]);
                }
            }
    
            // choosed book
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'book_id'        => $bookId
            ]);

            // Commit the transaction
            DB::commit();
    
            return response()->json([
                'success' => TRUE,
                'message' => 'Berhasil menambahkan akun.',
                'data'    => [],
            ], 200);
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollBack();
    
            return response()->json([
                'success' => FALSE,
                'message' => 'Terjadi kesalahan saat menambahkan akun: ' . $e->getMessage(),
                'data'    => [],
            ], 500);
        }
    }

    /**
     * @param integer id [transaksi id]
     */
    public function detail_peminjaman($id){
        $validTransactionId = $id != '' ? is_int((int)Crypt::decryptString($id)) ? (int)Crypt::decryptString($id) != 0 ? (int)Crypt::decryptString($id) : NULL : NULL : NULL;
        
        // cek apakah id dai data yang dipilih tidak null
        if ($validTransactionId == NULL) {
            return redirect()->back()->withErrors(array('error', 'Data yang dipilih tidak ditemukan'));
        }

        // ambil data setiap buku yang dipinjam yang berasal dari transaksi peminjaman
        $detailTransaksiItem = TransactionDetail::join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
        ->join('books', 'books.id', '=', 'transaction_details.book_id')
        ->where('transaction_id', $validTransactionId)->get();

        $dataTransaksi = Transaction::select('transactions.id', 'users.name', 'users.email', 'transactions.jenis_transaksi', 'transactions.tgl_pinjam', 'transactions.tgl_wajib_kembali', 'transactions.status_approval')
        ->where('transactions.id', $validTransactionId)
        ->join('users', 'users.id', '=', 'transactions.userid')
        ->first();

        if($dataTransaksi->status_approval == 'Approved'){
            $data['title'] = 'Detail Pengembalian Buku';
        } else {
            $data['title'] = 'Detail Peminjaman Buku';
        }

        $hariIni = date('Y-m-d');
        $convertNowDate = New DateTime($hariIni);

        $tglWajibKembali = $dataTransaksi->tgl_wajib_kembali;
        $convertTglWajibKembali = New DateTime($tglWajibKembali);

        // $interval = $hariIni-$tglWajibKembali;

        // dd($convertNowDate->diff($convertTglWajibKembali)->days);

        if($dataTransaksi->tgl_wajib_kembali < date('Y-m-d')) {
            $denda = 1000;
            $banyakBuku = count($detailTransaksiItem);
            $dif = $convertNowDate->diff($convertTglWajibKembali)->days;
            $totalDenda = $denda*$dif*$banyakBuku;
            $data['denda'] = $totalDenda;
        } else {
            $data['denda'] = 0;
        }


        $data['transaksi'] = $dataTransaksi;
        $data['transaksi_detail'] = $detailTransaksiItem;
        // dd(count($data['transaksi_detail']));
        $data['transaction_id'] = $id;

        // dd($detailTransaksiItem);
        return view('pages.approval_peminjaman.view', $data);
    }

    public function cancel_peminjaman(Request $request)
    {
        // Decrypt and validate transaction ID
        $validTransactionId = $request->id != '' ? is_int((int)Crypt::decryptString($request->id)) ? (int)Crypt::decryptString($request->id) != 0 ? (int)Crypt::decryptString($request->id) : NULL  : NULL : NULL;
    
        // Start the database transaction
        DB::beginTransaction();
    
        try {
            // Find the transaction
            $transaction = Transaction::find($validTransactionId);
    
            // If transaction not found, rollback and return error
            if (!$transaction) {
                DB::rollBack();
                return response()->json([
                    'success' => FALSE,
                    'message' => 'Maaf, transaksi tidak ditemukan.',
                    'data'    => [],
                ], 404);
            }

            if ($transaction->status_approval == 'Approved') {
                return response()->json([
                    'success' => FALSE,
                    'message' => 'Maaf, tidak dapat menghapus data, karena transaksi telah disetujui, silahkan hubungi admin.',
                    'data'    => [],
                ], 400);
            }
    
            // Get transaction details
            $transactionDetail = TransactionDetail::where('transaction_id', $validTransactionId)->get();
    
            $transactionDetailIds = [];
            $multipleBookIds = [];
            // Delete the transaction details
            foreach ($transactionDetail as $detail) {
                $transactionDetailIds[] = $detail->id;
                $multipleBookIds[]      = $detail->book_id;
                $detail->delete();
            }

            // mengambil data buku yang terlambatkan denda
            $getLoanedBook = Book::whereIn('id', $multipleBookIds)->get();

            $bookCategoryIds = [];
            foreach ($getLoanedBook as $list){
                $bookCategoryIds[]=$list->category_id;
            }

            // ambil data stock
            $getBookStock = BookStock::whereIn('category_id', $bookCategoryIds)->get();
            
            // if ($transaction->status_approval == 'Approved') {
            //     foreach ($getBookStock as $list){
            //         $list->total += 1;
            //         $list->save();
            //     }
            // }

            // Delete the transaction
            $transaction->delete();

            // Commit the transaction
            DB::commit();
    
            // Return a successful response
            return response()->json([
                'success' => TRUE,
                'message' => 'Data berhasil dihapus.',
                'data'    => [],
            ], 200);
    
        } catch (\Exception $e) {
            // If anything fails, rollback the transaction
            DB::rollBack();
            return response()->json([
                'success' => FALSE,
                'message' => 'Terjadi kesalahan, silahkan hubungi administrator: ' . $e,
                'data'    => [],
            ], 500);
        }
    }

    public function greet($name)
    {
        return response()->json(['message' => "Hello, $name!"]);
    }

}
