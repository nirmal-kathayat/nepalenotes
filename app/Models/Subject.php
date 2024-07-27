<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'subjects';
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }
}
