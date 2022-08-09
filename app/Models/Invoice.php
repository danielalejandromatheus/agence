<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table='cao_fatura';
    use HasFactory;
    public function service(){
        return $this->belongsTo(Service::class, 'co_os', 'co_os');
    }
}
