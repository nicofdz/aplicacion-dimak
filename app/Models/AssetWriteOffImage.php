<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetWriteOffImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_write_off_id',
        'image_path',
    ];

    public function writeOff()
    {
        return $this->belongsTo(AssetWriteOff::class, 'asset_write_off_id');
    }
}
