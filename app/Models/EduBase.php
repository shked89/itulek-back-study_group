<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EduBase extends Model
{
    protected $table = 'group.edu_bases';

    public $timestamps = false;

    protected $fillable = [
        'title',
    ];

}
