<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FichaRevision extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['ficha_id', 'revisao', 'dados', 'user_id', 'resumo'];

    protected $casts = [
        'dados'      => 'array',
        'created_at' => 'datetime',
    ];

    public function ficha(): BelongsTo
    {
        return $this->belongsTo(Ficha::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retorna um objeto Ficha (não persistido) populado com os dados desta revisão,
     * útil para renderizar o PDF de uma revisão antiga.
     */
    public function fichaSnapshot(): Ficha
    {
        $ficha = new Ficha($this->dados ?? []);
        $ficha->setRelation('integrante', $this->ficha->integrante);
        $ficha->revisao = $this->revisao;

        return $ficha;
    }
}
