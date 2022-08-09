<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'cao_os';
    protected $primaryKey = 'co_os';
    use HasFactory;
    public function invoices(){
        return $this->hasMany(Invoice::class, 'co_os', 'co_os');
    }
    public function user(){
        return $this->belongsTo(User::class, 'co_usuario');
    }
}
