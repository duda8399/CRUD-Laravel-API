<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Contact extends Model
{
    use HasFactory;
    protected $table = 'contacts';
    public $timestamps = false;
    
    protected $fillable = [
        'id',
        'name',
        'last_name',
        'phone',
        'email',
        'company_id',
    ];
}
