<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\CustomDateTimeCast;
use Illuminate\Database\Eloquent\SoftDeletes;
class ContactUs extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'contact_us';
    protected $fillable = [
        'name',
        'state',
        'city',
        'area',
        'phone',
        
    ];

    protected $allowedSorts = [
       
        'created_at',
        'updated_at'
    ];

    protected $hidden = ['deleted_at'];

    
}
