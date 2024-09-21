<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tasks extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public $primaryKey = 'id';

    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'description',
        'status', # 'pending', 'progress', 'conclusion'
        'user_id',
        'duedate_at',
    ];

    /**
     * Get the user that owns the Tasks
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the tasks_relationship for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasksRelationship()
    {
        return $this->hasMany(TasksRelationship::class, 'task_id', 'id');
    }
}
