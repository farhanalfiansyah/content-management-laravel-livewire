<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'short_description',
        'image',
        'status',
        'published_at',
        'user_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the categories for the post.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->status === 'published' && $this->published_at?->isPast();
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        return asset('images/posts/' . $this->image);
    }

    public function getExcerptAttribute(): string
    {
        return $this->short_description ?: Str::limit(strip_tags($this->content), 150);
    }

    public function getReadingTimeAttribute(): string
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / 200); // Average reading speed is 200 words per minute
        
        return $minutes . ' min read';
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
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

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = static::generateSlug($post->title);
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && empty($post->getOriginal('slug'))) {
                $post->slug = static::generateSlug($post->title);
            }
        });
    }
}
