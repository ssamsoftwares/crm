<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'email', 'phone_number', 'company_name', 'status', 'alloted_date', 'communication_medium', 'project_details'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'customer_id');
    }

    protected $casts = [
        'updated_at' => 'datetime',
    ];

    // Accessor for the last_updated (updated_at) attribute

    public function getLastUpdatedAttribute()
    {
        $today = now()->format('Y-m-d');
        if ($this->updated_at->toDateString() === $today) {
            return 'Today at ' . $this->updated_at->format('h:i a');
        } elseif ($this->updated_at->addDay()->toDateString() === $today) {
            return 'Yesterday at ' . $this->updated_at->format('h:i a');
        } else {
            // return $this->updated_at->format('M j, Y h:i a');
            return $this->updated_at->format('d-M-Y h:i a');
        }
    }
}
