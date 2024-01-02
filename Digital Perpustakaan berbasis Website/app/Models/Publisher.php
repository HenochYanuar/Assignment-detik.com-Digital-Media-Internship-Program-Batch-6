<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    use HasFactory;
    
    protected $fillable = ['name'];

    public function books(){
        return $this->hasMany(Book::class,'id_publisher');
    }
}


