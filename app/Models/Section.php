<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'order'];

    public function subSections()
    {
        return $this->hasMany(SubSection::class)->orderBy('order');
    }
}
