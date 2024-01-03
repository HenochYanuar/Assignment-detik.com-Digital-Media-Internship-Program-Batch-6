<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookFile extends Model
{
    use HasFactory;

    protected $table = 'book_files';

    protected $fillable = ['cover_path','cover_mime', 'cover_image', 'pdf_path', 'pdf_mime', 'pdf_file'];

    public function books(){
        return $this->hasOne(Book::class,'id_file');
    }
}
