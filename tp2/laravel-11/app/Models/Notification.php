<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    // Specify the fillable fields
    protected $fillable = [
        'title',
        'message',
        'is_read',
    ];

    // Define any casting if needed
    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Scope to get only unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Accessor to format the created_at date
     */
    public function getFormattedCreatedAtAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }
}
