<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TasksRelationship extends Model
{
    use HasFactory;

    protected $table = 'tasks_relationship';

    protected $fillable = [
        'task_id',
        'user_id'
    ];

    /**
     * Get the user that owns the TasksRelationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tasks that owns the TasksRelationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tasks(): BelongsTo
    {
        return $this->belongsTo(Tasks::class);
    }
}
