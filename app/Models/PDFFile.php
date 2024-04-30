<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PDFFile extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['name', 'path'];


    public $table = 'pdf_files';

}


