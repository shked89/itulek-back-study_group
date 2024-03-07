<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyGroup extends Model
{
    protected $table = 'group.studies_groups';
    public $timestamps = false;

    
    protected $fillable = [
        'start_year',
        'college_id',
        'adviser_id',
        'department_id',
        'speciality_id',
        'edu_base_id',
    ];

    public function refStudyGroupToPersons()
    {
        return $this->hasMany(RefStudyGroupToPerson::class, 'study_group_id');
    }

    public function refStudyGroupToQualifications()
    {
        return $this->hasMany(RefStudyGroupToQualification::class, 'study_group_id');
    }

    public function studyGroupInfo()
    {
        return $this->hasOne(RefStudyGroupInfo::class, 'study_group_id');
    }
    public function eduBase()
    {
        return $this->belongsTo(EduBase::class, 'edu_base_id');
    }

    public function studyGroups()
{
    return $this->belongsToMany(StudyGroup::class, 'group.ref_study_group_curriculums', 'curriculum_id', 'study_group_id');
}

}
