<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'title',
        'duration_minutes',
        'is_completed',
        'completed_at',
        'order'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
