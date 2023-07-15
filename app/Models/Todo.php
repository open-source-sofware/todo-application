<?php

namespace App\Models;

use App\Enums\TodoStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    protected $casts = [
        'status' => TodoStatusEnum::class
    ];

    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
