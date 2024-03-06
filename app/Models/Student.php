<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'group.students';

    protected $fillable = [
        'person_unit_id',
        'college_id',
    ];
}
