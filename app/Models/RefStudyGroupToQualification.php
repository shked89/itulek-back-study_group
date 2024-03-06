<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefStudyGroupToQualification extends Model
{
    protected $table = 'group.ref_study_group_to_qualifications';

    protected $primaryKey = 'study_group_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'study_group_id',
        'qualification_id',
    ];

    public function studyGroup()
    {
        return $this->belongsTo(StudyGroup::class, 'study_group_id');
    }
}
