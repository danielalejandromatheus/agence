<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table= 'permissao_sistema';
    protected $primaryKey = 'co_usuario';
    use HasFactory;
}
