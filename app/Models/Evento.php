<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'eventos';

    protected $fillable = ['data', 'titulo', 'descricao', 'ordem'];

    protected $casts = ['data' => 'date'];

    public function scopeOrdenados($query)
    {
        return $query->orderBy('data');
    }
}
