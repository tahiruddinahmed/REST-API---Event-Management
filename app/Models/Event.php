<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'start_time', 'end_time', 'user_id'];
    /**
     * User relationship 
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    /**
     * Attendee Relationship - One to many
     */
    public function attendees(): HasMany {
        return $this->hasMany(Attendee::class);
    }
}
