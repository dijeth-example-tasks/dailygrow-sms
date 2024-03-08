<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskRun extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'messages_count', 'errors_count', 'date'];
    protected $casts = [
        'date' => 'immutable_datetime:Y-m-d H:i:s',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
