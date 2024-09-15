<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tasks extends Model
{
    use HasFactory, SoftDeletes;

	protected $guarded	 = ['id'];

	public $primaryKey	 = 'id';

    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'description',
        'status', # 'pending', 'progress', 'conclusion'
        'duedate_at'
    ];

}
