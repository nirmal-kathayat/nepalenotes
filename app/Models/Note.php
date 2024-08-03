<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $guarded = [];
    protected $table = 'notes';
    public function images()
    {
        return $this->hasMany(NoteImage::class);
    }
}
