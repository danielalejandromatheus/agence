<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;
    protected $table = 'cao_usuario';
    protected $primaryKey = 'co_usuario';
    public $incrementing = false;
    protected $keyType = 'string';
    public function permission(){
        return $this->hasOne(Permission::class, 'co_usuario', 'co_usuario');
    }
    public function invoices(){
        return $this->hasManyThrough(Invoice::class,Service::class, 'co_usuario', 'co_os', 'co_usuario', 'co_os');
    }
    public function salary(){
        return $this->hasOne(Salary::class, 'co_usuario', 'co_usuario');
    }
}
