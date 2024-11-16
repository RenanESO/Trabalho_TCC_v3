<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class GoogleToken extends Model
{
    // Define a tabela associada ao modelo (caso não siga o padrão Laravel de pluralização)
    protected $table = 'google_tokens';

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'expires_in',
        'scope',
        'token_type',
        'created',
        'user_id',
        'access_token',
        'refresh_token',
    ];

    /**
     * Verifica se o token está expirado.
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->expires_at ? $this->expires_at->isPast() : true;
    }

    /**
     * Associa o token ao usuário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtém o token de autenticação do usuário autenticado.
     *
     * @return GoogleToken|null
     */
    public static function getUserToken()
    {
        return self::where('user_id', Auth::id())->first();
    }
}
