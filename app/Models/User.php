<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Los atributos que se pueden asignar de forma masiva.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'profile_photo_path',
        'rut',
        'address',
        'phone',
        'cargo',
        'departamento',
        'license_expires_at',
        'license_photo_path',
        'role',
        'must_change_password',
        'is_active',
    ];

    /**
     * Los atributos que deben ocultarse para la serialización.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Obtiene los atributos que deben ser convertidos (casting).
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'license_expires_at' => 'date',
            'must_change_password' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function vehicleRequests()
    {
        return $this->hasMany(VehicleRequest::class);
    }

    public function getShortNameAttribute(): string
    {
        // Retorna "PrimerNombre PrimerApellido"
        $firstName = explode(' ', $this->name)[0];
        $firstLastName = explode(' ', $this->last_name ?? '')[0];

        return trim("$firstName $firstLastName");
    }
}
