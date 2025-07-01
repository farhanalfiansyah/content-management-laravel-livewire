<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'body',
        'status',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->status === 'published';
    }

    public function getExcerptAttribute(): string
    {
        return Str::limit(strip_tags($this->body), 150);
    }

    public function getReadingTimeAttribute(): string
    {
        $wordCount = str_word_count(strip_tags($this->body));
        $minutes = ceil($wordCount / 200); // Average reading speed is 200 words per minute
        
        return $minutes . ' min read';
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'LIKE', "$slug%")->count();
        
        return $count ? "{$slug}-{$count}" : $slug;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = static::generateSlug($page->title);
            }
        });

        static::updating(function ($page) {
            if ($page->isDirty('title') && empty($page->getOriginal('slug'))) {
                $page->slug = static::generateSlug($page->title);
            }
        });
    }
} 