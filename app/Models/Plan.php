<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Ramsey\Uuid\Uuid;

class Plan extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, SoftDeletes, hasUuids;

    protected $keyType = 'string'; // Define o tipo da chave primária como string
    public $incrementing = false; // Desabilita a auto-incrementação para UUIDs
    protected $primaryKey = 'id';

    protected $table = 'plans'; // Define o nome da tabela

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString(); // Gera um UUID para o novo plano
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'price',
        'qtd_users',
        'qtd_wallets',
        'qtd_transactions',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'qtd_users' => 'integer',
        'qtd_wallets' => 'integer',
        'qtd_transactions' => 'integer',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

}
