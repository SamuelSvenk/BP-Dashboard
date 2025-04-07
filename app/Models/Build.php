<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Build extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'build_number',
        'repository',
        'branch',
        'commit_hash',
        'commit_message',
        'status',
        'completed_at',
    ];
    
    protected $casts = [
        'completed_at' => 'datetime',
    ];
}