<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StudyGroupService;
use Illuminate\Support\Facades\Validator;

class StudyGroupController extends Controller
{
    protected $studyGroupService;

    public function __construct(StudyGroupService $studyGroupService)
    {
        $this->studyGroupService = $studyGroupService;
    }

    //Создание Группы для студентов и подробной информации о группе
    public function createStudyGroup(Request $request)
    {
        // Извлекаем параметры запроса
        $queryParams = $request->query();

        // Правила валидации
        $rules = [
            'start_year' => 'required|numeric',
            'college_id' => 'required|numeric',
            'adviser_id' => 'required|numeric',
            'department_id' => 'required|numeric',
            'speciality_id' => 'required|numeric',
            'qualification_ids' => 'sometimes|array',
            'qualification_ids.*' => 'numeric|distinct',
            'title' => 'sometimes|string|max:255',
            'language_iso' => 'sometimes|string|max:255',
        ];


        // Валидация
        $validator = Validator::make($queryParams, $rules);

        // Проверка на ошибки валидации
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Данные прошли валидацию
        $validated = $validator->validated();

        $result = $this->studyGroupService->createStudyGroup($validated);
        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 409); // Конфликт
        }


        return response()->json(['message' => 'Study group and related data created successfully', 'data' => $result]);
    }




    public function updateStudyGroup(Request $request)
    {
        // Извлекаем все query параметры
        $queryParams = $request->query();

        // Проверяем наличие обязательного параметра groupId
        if (!isset($queryParams['groupId'])) {
            return response()->json(['error' => 'The groupId query parameter is required.'], 400); // Bad Request
        }

        // Извлекаем и удаляем groupId из массива параметров, чтобы оставить только данные для обновления
        $groupId = $queryParams['groupId'];
        unset($queryParams['groupId']);

        // Передаем оставшиеся данные в сервис для обновления
        $result = $this->studyGroupService->updateStudyGroup($groupId, $queryParams);

        // Проверка результата и ответ
        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 409); // Конфликт или другой подходящий код состояния
        }

        return response()->json(['message' => 'Study group updated successfully', 'data' => $result]);
    }

    public function indexStudyGroup(Request $request)
    {
        $collegeId = $request->query('college_id');

        $result = $this->studyGroupService->getAllStudyGroups($collegeId);

        return response()->json($result);
    }

    // public function showStudyGroupById($id)
    // {
    //     return $this->studyGroupService->getStudyGroupById($id);
    // }

    public function deleteStudyGroup(Request $request)
    {
        // Извлечение groupId из параметров запроса
        $groupId = $request->query('groupId');

        // Обработка отсутствия groupId
        if (!$groupId) {
            return response()->json(['error' => 'The groupId parameter is required.'], 400); // Bad Request
        }

        try {
            $result = $this->studyGroupService->deleteStudyGroup($groupId);
            return response()->json($result);
        } catch (\Exception $e) {
            // Обработка исключения, например, если группа не найдена
            return response()->json(['error' => $e->getMessage()], 404); // Not Found или другой подходящий код состояния
        }
    }

    public function getTitle(Request $request)
    {
        $studyGroupId = $request->query('study_group_id');
        
        if (!$studyGroupId) {
            return response()->json(['error' => 'The study_group_id query parameter is required.'], 400);
        }

        $title = $this->studyGroupService->getTitleByStudyGroupId($studyGroupId);

        if (is_null($title)) {
            return response()->json(['error' => 'No title found for the provided study_group_id.'], 404);
        }

        return response()->json(['title' => $title]);
    }

}
