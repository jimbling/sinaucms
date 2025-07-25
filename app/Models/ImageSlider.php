<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageSlider extends Model
{
    use HasFactory;
    protected $table = 'image_sliders';
    protected $fillable = [
        'caption',
        'path',
    ];
}
