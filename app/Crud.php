<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crud extends Model
{
	protected $table = 'crud';
    protected $primaryKey = 'id';
    protected $fillable = ['judul', 'isi', 'gambar'];
    public $timestamps = false;
}
