<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetWriteOff extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'user_id',
        'motivo',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(AssetWriteOffImage::class);
    }
}
