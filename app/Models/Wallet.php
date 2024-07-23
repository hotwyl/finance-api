<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Wallet extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string'; // Define o tipo da chave primária como string
    public $incrementing = false; // Desabilita a auto-incrementação para UUIDs

    protected $fillable = [
        'user_id', 'name', 'balance',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString(); // Gera um UUID para a nova carteira
        });
    }

    // Relacionamento com o usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento com as transações
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
