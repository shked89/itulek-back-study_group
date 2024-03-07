<?php

namespace App\Services;

use App\Models\Curriculum;
use App\Models\EduBase;
use App\Models\StudyGroup;
use App\Models\StudyGroupCurriculum;
use Illuminate\Support\Facades\DB;
use App\Services\ExternalSpecialityService;



class CurriculumService
{
    public function createCurriculumAndStudyGroups($data)
    {
        DB::beginTransaction();
        try {
            // Подготавливаем данные для Curriculum, исключая необязательные поля, если они не предоставлены
            $curriculumData = [
                'title' => $data['title'],
                'year' => $data['year'],
                'college_id' => $data['college_id'],
                // Добавляем необязательные поля, если они есть
            ];

            if (isset($data['status_delete'])) {
                $curriculumData['status_delete'] = $data['status_delete'];
            }

            if (isset($data['file_url'])) {
                $curriculumData['file_url'] = $data['file_url'];
            }

            // Создаем Curriculum
            $curriculum = Curriculum::create($curriculumData);

            // Создаем связи с StudyGroup, если они есть
            if (!empty($data['study_group_ids'])) {
                foreach ($data['study_group_ids'] as $studyGroupId) {
                    StudyGroupCurriculum::create([
                        'study_group_id' => $studyGroupId,
                        'curriculum_id' => $curriculum->id,
                    ]);
                }
            }

            DB::commit();
            return $curriculum;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }



    public function getCurriculumWithFilters($filters)
    {
        $query = Curriculum::query()->where('status_delete', false); // Добавляем фильтр для исключения удаленных учебных планов

        if (isset($filters['curriculum_id'])) {
            $query->where('id', $filters['curriculum_id']);
        }

        if (isset($filters['college_id'])) {
            $query->where('college_id', $filters['college_id']);
        }

        if (isset($filters['year'])) { // Добавляем проверку на наличие фильтра по году
            $query->where('year', $filters['year']);
        }

        $externalSpecialityService = new ExternalSpecialityService();

        $curriculums = $query->with(['studyGroups' => function ($query) {
            $query->with(['studyGroupInfo', 'eduBase']);
        }])->get();

        foreach ($curriculums as $curriculum) {
            if ($curriculum->studyGroups->isNotEmpty()) {

                $firstGroup = $curriculum->studyGroups->first();
                $curriculum->speciality_title = $externalSpecialityService->getSpecialityById($firstGroup->speciality_id);
                $curriculum->edu_base = $firstGroup->eduBase->title ?? null; // Убедитесь, что у вас есть связь eduBase

                // Здесь можно убрать специфические данные для группы, если они не нужны в выводе.
                // Если вам всё же нужны данные по группам без speciality и edu_base, просто пропустите этот шаг.
                foreach ($curriculum->studyGroups as $group) {
                    unset($group->eduBase); // Удаляем объект eduBase, если он больше не нужен в каждой группе
                }
            }
        }

        return $curriculums;
    }
    public function getCurriculumWithFiltersDelete($filters)
    {
        $query = Curriculum::query()->where('status_delete', true); // Добавляем фильтр для исключения удаленных учебных планов

        if (isset($filters['curriculum_id'])) {
            $query->where('id', $filters['curriculum_id']);
        }

        if (isset($filters['college_id'])) {
            $query->where('college_id', $filters['college_id']);
        }

        if (isset($filters['year'])) { // Добавляем проверку на наличие фильтра по году
            $query->where('year', $filters['year']);
        }

        $externalSpecialityService = new ExternalSpecialityService();

        $curriculums = $query->with(['studyGroups' => function ($query) {
            $query->with(['studyGroupInfo', 'eduBase']);
        }])->get();

        foreach ($curriculums as $curriculum) {
            if ($curriculum->studyGroups->isNotEmpty()) {

                $firstGroup = $curriculum->studyGroups->first();
                $curriculum->speciality_title = $externalSpecialityService->getSpecialityById($firstGroup->speciality_id);
                $curriculum->edu_base = $firstGroup->eduBase->title ?? null; // Убедитесь, что у вас есть связь eduBase

                // Здесь можно убрать специфические данные для группы, если они не нужны в выводе.
                // Если вам всё же нужны данные по группам без speciality и edu_base, просто пропустите этот шаг.
                foreach ($curriculum->studyGroups as $group) {
                    unset($group->eduBase); // Удаляем объект eduBase, если он больше не нужен в каждой группе
                }
            }
        }

        return $curriculums;
    }

    public function changeStatusDelete($curriculumId, $status)
    {
        DB::beginTransaction();
        try {
            $curriculum = Curriculum::findOrFail($curriculumId);
            $curriculum->status_delete = $status;
            $curriculum->save();
            DB::commit();
            return ['success' => true, 'message' => 'Status delete has been updated successfully.'];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => 'Error occurred while updating status delete: ' . $e->getMessage()];
        }
    }

    public function getAllStudyGroups($filters = [])
    {
        // Проверяем, предоставлены ли фильтры
        if (empty($filters['college_id']) || empty($filters['edu_base_id']) || empty($filters['speciality_id'])) {
            return []; // Возвращаем пустой массив, если фильтры не предоставлены
        }

        $query = StudyGroup::query()->with(['eduBase', 'studyGroupInfo']);

        // Применяем фильтры, если они предоставлены
        if (!empty($filters['college_id'])) {
            $query->where('college_id', $filters['college_id']);
        }

        if (!empty($filters['edu_base_id'])) {
            $query->where('edu_base_id', $filters['edu_base_id']);
        }

        if (!empty($filters['speciality_id'])) {
            $query->where('speciality_id', $filters['speciality_id']);
        }

        $studyGroups = $query->get();

        // Трансформация результатов
        $result = $studyGroups->map(function ($studyGroup) {
            return [
                'id' => $studyGroup->id,
                'title' => $studyGroup->studyGroupInfo ? $studyGroup->studyGroupInfo->title : null,
            ];
        });

        return $result;
    }
    public function getAllEduBases()
    {
        return EduBase::all();
    }

    public function updateCurriculumAndStudyGroups($curriculumId, $data)
{
    DB::beginTransaction();
    try {
        // Находим Curriculum по ID
        $curriculum = Curriculum::findOrFail($curriculumId);

        // Обновляем данные Curriculum
        $curriculum->update([
            'title' => $data['title'],
            'year' => $data['year'],
            'college_id' => $data['college_id'],
            'status_delete' => $data['status_delete'] ?? $curriculum->status_delete, // Обновляем, если предоставлено
            'file_url' => $data['file_url'] ?? $curriculum->file_url, // Обновляем, если предоставлено
        ]);

        // Обновляем связи с StudyGroup, если они есть
        if (!empty($data['study_group_ids'])) {
            // Удаляем существующие связи
            StudyGroupCurriculum::where('curriculum_id', $curriculum->id)->delete();
            
            // Создаем новые связи
            foreach ($data['study_group_ids'] as $studyGroupId) {
                StudyGroupCurriculum::create([
                    'study_group_id' => $studyGroupId,
                    'curriculum_id' => $curriculum->id,
                ]);
            }
        }

        DB::commit();
        return $curriculum;
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}
}
