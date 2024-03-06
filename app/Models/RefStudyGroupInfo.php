<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefStudyGroupInfo extends Model
{
    protected $table = 'group.study_group_info';

    

    protected $fillable = [
        'title',
        'language_iso',
        'study_group_id',
    ];

    public function studyGroup()
    {
        return $this->belongsTo(StudyGroup::class, 'study_group_id');
    }
}
