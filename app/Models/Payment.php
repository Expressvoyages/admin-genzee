<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id', 'user_id', 'amount', 'status'];
    
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}
