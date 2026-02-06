<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    const PRIORITY_LOW = 0;
    const PRIORITY_MEDIUM = 1;
    const PRIORITY_HIGH = 2;
    
    protected $fillable = [
        'title',
        'description',
        'scheduled_date',
        'scheduled_time',
        'completed',
        'user_id',
        'category_id',
        'priority',
        'due_date'
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'due_date' => 'datetime',
        'completed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    
    public function getFullScheduleAttribute()
    {
        if ($this->scheduled_date && $this->scheduled_time) {
            return $this->scheduled_date->format('Y-m-d') . ' ' . $this->scheduled_time;
        }
        return null;
    }
    
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }
    
    public function scopeIncomplete($query)
    {
        return $query->where('completed', false);
    }
    
    public function scopeComplete($query)
    {
        return $query->where('completed', true);
    }
    
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('completed', false);
    }
}