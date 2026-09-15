<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Integrante extends Model
{
    use HasFactory;

    protected $table = 'integrantes';

    protected $fillable = ['apelido', 'slug', 'cargo', 'foto', 'bio', 'ativo', 'ordem'];

    protected $casts = ['ativo' => 'boolean'];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function ficha()
    {
        return $this->hasOne(Ficha::class);
    }

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true)->orderBy('ordem');
    }

    /**
     * Slug público do integrante (gerado a partir do apelido se ausente).
     */
    public function slugPublico(): string
    {
        return $this->slug ?: str($this->apelido)->slug()->toString();
    }
}
