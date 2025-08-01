<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'image',
        'post_type',
        'post_counter',
        'author_id',
        'komentar_status',
        'status',
        'published_at'
    ];

    // Menambahkan properti $casts
    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsToMany(Category::class, 'category_post');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Relasi dengan Tag
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    public function getPublishedAtIndoAttribute()
    {
        return $this->published_at ? $this->published_at->translatedFormat('d F Y') : null;
    }
}
