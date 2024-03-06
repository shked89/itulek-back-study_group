<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefStudyGroupToPerson extends Model
{
    protected $table = 'group.ref_study_group_to_persons';

    protected $fillable = [
        'person_unit_id',
        'study_group_id',
    ];

    public function studyGroup()
    {
        return $this->belongsTo(StudyGroup::class, 'study_group_id');
    }
}
