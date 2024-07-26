<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Ramsey\Uuid\Uuid;

class Wallet extends Model
{
    use HasFactory, HasApiTokens, Notifiable, SoftDeletes, hasUuids;
    protected $keyType = 'string'; // Define o tipo da chave primária como string
    public $incrementing = false; // Desabilita a auto-incrementação para UUIDs
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'user_id'];

    protected $hidden = ['user_id', 'created_at', 'updated_at', 'deleted_at'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString(); // Gera um UUID para a nova carteira
        });
    }

    // Relacionamento com o wallet
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
