<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'sale_area_id_1',
        'sale_area_id_2',
    ];

    public function firstSale()
    {
        return $this->belongsTo(SaleArea::class, 'sale_area_id_1');
    }

    public function secondSale()
    {
        return $this->belongsTo(SaleArea::class, 'sale_area_id_2');
    }

    public function scopeSearchBySaleName($query, $saleName)
    {
        return $query->where(function ($query) use ($saleName) {
            $query->whereHas('firstSale', function ($query) use ($saleName) {
                $query->where('name', 'LIKE', "%$saleName%");
            })
            ->orWhereHas('secondSale', function ($query) use ($saleName) {
                $query->where('name', 'LIKE', "%$saleName%");
            });
        });
    }

   

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
