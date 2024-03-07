<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    use HasFactory;

    protected $table = 'group.curriculums';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'year',
        'college_id',
        'status_delete',
        'file_url',
    ];

    public function studyGroups()
    {
        return $this->belongsToMany(StudyGroup::class, 'group.ref_study_group_curriculums', 'curriculum_id', 'study_group_id');
    }

    // Здесь можно добавить отношения, например, с колледжами или специальностями
}
