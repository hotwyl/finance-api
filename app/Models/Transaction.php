<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Ramsey\Uuid\Uuid;

class Transaction extends Model
{
    use HasFactory, HasApiTokens, Notifiable, SoftDeletes, hasUuids;
    protected $keyType = 'string'; // Define o tipo da chave primária como string
    public $incrementing = false; // Desabilita a auto-incrementação para UUIDs
    protected $primaryKey = 'id';

    protected $fillable = [
        'wallet_id', 'type', 'amount', 'description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString(); // Gera um UUID para a nova transação
        });
    }

    // Relacionamento com a carteira (wallet)
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
