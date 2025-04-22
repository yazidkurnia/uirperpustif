<?php

namespace App\Models\Book;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    public static function getTopBook()
    {
        return DB::select('
                            SELECT 
                                b.id AS bookid,
                                b.judul as title,
                                category_id,
                                b.image_url as img_url,
                                COUNT(td.transaction_id) AS transaction_count
                            FROM 
                                books b
                            JOIN 
                                transaction_details td ON b.id = td.book_id
                            JOIN 
                                transactions t ON td.transaction_id = t.id
                            GROUP BY 
                                b.id,category_id
                            ORDER BY 
                                transaction_count DESC
                            LIMIT 5
        ');
    }

    public static function get_all_book(){
        return DB::select('
            SELECT 
                b.id AS bookid,
                b.judul as title,
                b.category_id,
                b.image_url as img_url
            FROM books as b
            JOIN book_stocks bs ON bs.category_id = b.category_id
            WHERE bs.total >= 0
        ');
    }

    public static function get_book_from_multiids(array $ids) {
        return DB::table('books')
                 ->whereIn('id', $ids)
                 ->get();
    }
}
