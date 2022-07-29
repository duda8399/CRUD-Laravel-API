<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contacts;

class Company extends Model
{
    use HasFactory;
    protected $table = 'companies';
    public $timestamps = false;
    
    protected $fillable = [
        'id',
        'cnpj',
        'social_name',
        'address',
        'email'
    ];


    public function contacts()
    {
	    return $this->hasMany(Contact::class);
    }
}
