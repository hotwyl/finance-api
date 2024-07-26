<?php

namespace App\Models;

 use Illuminate\Contracts\Auth\MustVerifyEmail;
 use Illuminate\Database\Eloquent\Concerns\HasUuids;
 use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
 use Laravel\Sanctum\HasApiTokens;
 use Ramsey\Uuid\Uuid;
 use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, SoftDeletes, hasUuids;

    protected $keyType = 'string'; // Define o tipo da chave primária como string
    public $incrementing = false; // Desabilita a auto-incrementação para UUIDs
    protected $primaryKey = 'id';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString(); // Gera um UUID para o novo usuário
        });
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];



    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relacionamento com as carteiras
    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }
}
