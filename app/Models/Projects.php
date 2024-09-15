<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Projects extends Model
{
    use  HasFactory, SoftDeletes;

	protected $guarded	 = ['id'];

	public $primaryKey	 = 'id';

    protected $table = 'projects';

    protected $fillable = [
        'title',
        'description',
        'conclusion_at'
    ];

}
