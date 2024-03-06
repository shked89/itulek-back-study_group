<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\StudyGroup;
use App\Models\RefStudyGroupInfo;
use App\Models\RefStudyGroupToQualification;
class StudyGroupService
{
    public function createStudyGroup($data)
    {
        if (isset($data['title'])) {
            $existingTitle = RefStudyGroupInfo::where('title', $data['title'])->first();
            if ($existingTitle) {
                return ['error' => 'Study group title already exists.'];
            }
        }
    
        // Создаем учебную группу, если предыдущие проверки прошли успешно
        $studyGroup = StudyGroup::create([
            'start_year' => $data['start_year'],
            'college_id' => $data['college_id'],
            'adviser_id' => $data['adviser_id'],
            'department_id' => $data['department_id'],
            'speciality_id' => $data['speciality_id'],
        ]);
    
        if (!empty($data['qualification_ids']) && is_array($data['qualification_ids'])) {
            foreach (array_unique($data['qualification_ids']) as $qualificationId) {
                RefStudyGroupToQualification::create([
                    'study_group_id' => $studyGroup->id,
                    'qualification_id' => $qualificationId,
                ]);
            }
        }
    
        if (isset($data['title']) && isset($data['language_iso'])) {
            RefStudyGroupInfo::create([
                'study_group_id' => $studyGroup->id,
                'title' => $data['title'],
                'language_iso' => $data['language_iso'],
            ]);
        }
    
        return $studyGroup;
    }

    //Обновление данных Студ Группы
    public function updateStudyGroup($groupId, $data)
    {
        // Обновление данных учебной группы
        $studyGroup = StudyGroup::findOrFail($groupId);
        $studyGroup->update($data);

        // Обновление или добавление квалификаций
        if (isset($data['qualification_ids']) && is_array($data['qualification_ids'])) {
            // Очистка существующих связей
            RefStudyGroupToQualification::where('study_group_id', $groupId)->delete();

            // Создание новых связей
            foreach ($data['qualification_ids'] as $qualificationId) {
                RefStudyGroupToQualification::create([
                    'study_group_id' => $groupId,
                    'qualification_id' => $qualificationId,
                ]);
            }
        }

        // Обновление или добавление информации о группе
        if (isset($data['title']) && isset($data['language_iso'])) {
            RefStudyGroupInfo::updateOrCreate(
                ['study_group_id' => $groupId],
                ['title' => $data['title'], 'language_iso' => $data['language_iso']]
            );
        }

        return $studyGroup;
    }

    //Вывод Всхе групп
    public function getAllStudyGroups($collegeId = null)
    {
        // Загрузка связанных данных с помощью жадной загрузки
        $query = StudyGroup::with(['refStudyGroupToQualifications', 'studyGroupInfo']);

        if (!is_null($collegeId)) {
            $query->where('college_id', $collegeId);
        }

        return $query->get();
    }
    //Вывод групп по id
    // public function getStudyGroupById($id)
    // {
    //     $studyGroup = StudyGroup::find($id);
    //     if (!$studyGroup) {
    //         return response()->json(['message' => 'StudyGroup not found'], 404);
    //     }
    //     return response()->json($studyGroup);
    // }

    //вывод по id

    public function deleteStudyGroup($groupId)
    {
        $studyGroup = StudyGroup::findOrFail($groupId);
        
        RefStudyGroupToQualification::where('study_group_id', $groupId)->delete();
        RefStudyGroupInfo::where('study_group_id', $groupId)->delete();
        
        // Удаление учебной группы
        $studyGroup->delete();
        
        return ['message' => 'Study group deleted successfully.'];
    }

    public function getTitleByStudyGroupId($studyGroupId)
    {
        $studyGroupInfo = RefStudyGroupInfo::where('study_group_id', $studyGroupId)
                                            ->first(['title']); // Извлекаем только поле title

        return $studyGroupInfo ? $studyGroupInfo->title : null;
    }


}
