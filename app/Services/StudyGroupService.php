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
        $studyGroup = StudyGroup::create([
            'start_year' => $data['start_year'],
            'college_id' => $data['college_id'],
            'adviser_id' => $data['adviser_id'],
            'department_id' => $data['department_id'],
            'speciality_id' => $data['speciality_id'],
        ]);

        if (isset($data['qualification_id'])) {
            RefStudyGroupToQualification::create([
                'study_group_id' => $studyGroup->id,
                'qualification_id' => $data['qualification_id'],
            ]);
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
    public function updateStudyGroupWithDetails($studyGroupId, array $studyGroupData, array $studyGroupInfoData)
    {
        $result = DB::transaction(function () use ($studyGroupId, $studyGroupData, $studyGroupInfoData) {

            $studyGroup = StudyGroup::findOrFail($studyGroupId);
            $studyGroup->update($studyGroupData);


            $studyGroupInfo = RefStudyGroupInfo::where('study_group_id', $studyGroupId)->first();
            if ($studyGroupInfo) {
                // Если информация существует, обновляем ее
                $studyGroupInfo->update($studyGroupInfoData);
            } else {
                // Если информации нет, создаем новую запись
                $studyGroupInfoData['study_group_id'] = $studyGroupId;
                $studyGroupInfo = RefStudyGroupInfo::create($studyGroupInfoData);
            }

            return [
                'studyGroup' => $studyGroup->fresh(),
                'studyGroupInfo' => $studyGroupInfo,
            ];
        });

        return response()->json($result);
    }

    //Вывод Всхе групп
    public function getAllStudyGroups()
    {
        $studyGroups = StudyGroup::all();
        return response()->json($studyGroups);
    }
    //Вывод групп по id
    public function getStudyGroupById($id)
    {
        $studyGroup = StudyGroup::find($id);
        if (!$studyGroup) {
            return response()->json(['message' => 'StudyGroup not found'], 404);
        }
        return response()->json($studyGroup);
    }

    //вывод по id
    public function deleteStudyGroup($id)
    {
        $studyGroup = StudyGroup::with(['refStudyGroupToPersons', 'refStudyGroupToQualifications', 'studyGroupInfo'])->find($id);
        
        if (!$studyGroup) {
            return response()->json(['message' => 'StudyGroup not found'], 404);
        }
        
        // Удаляем связанные данные
        try {
            DB::transaction(function () use ($studyGroup) {
                // Проверка и удаление связанных записей refStudyGroupToPersons
                if ($studyGroup->refStudyGroupToPersons()->exists()) {
                    $studyGroup->refStudyGroupToPersons()->delete();
                }
    
                // Проверка и удаление связанных записей refStudyGroupToQualifications
                if ($studyGroup->refStudyGroupToQualifications()->exists()) {
                    $studyGroup->refStudyGroupToQualifications()->delete();
                }
    
                // Проверка и удаление связанных записей studyGroupInfo
                if ($studyGroup->studyGroupInfo()->exists()) {
                    $studyGroup->studyGroupInfo()->delete();
                }
    
                // После удаления всех связанных данных, удаляем саму группу учебы
                $studyGroup->delete();
            });
    
            return response()->json(['message' => 'StudyGroup and related data deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting StudyGroup and related data', 'error' => $e->getMessage()], 500);
        }
}
}
