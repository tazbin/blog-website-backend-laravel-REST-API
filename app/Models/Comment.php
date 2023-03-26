<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'blog_id',
        'commenter_id',
        'content',
    ];

    public function blog() {
        return $this->belongsTo(Blog::class);
    }

    public function commenter() {
        return $this->belongsTo(User::class);
    }
}
