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
}
