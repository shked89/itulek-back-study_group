<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class StudyGroupCurriculum extends Pivot
{
    protected $table = 'group.ref_study_group_curriculums';

    // Отключаем автоинкремент
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'study_group_id',
        'curriculum_id',
    ];

    // Отношение к StudyGroup
    public function studyGroup()
    {
        return $this->belongsTo(StudyGroup::class, 'study_group_id');
    }

    // Отношение к Curriculum
    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
    }
}
