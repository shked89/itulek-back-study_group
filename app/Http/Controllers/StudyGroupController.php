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
            'qualification_id' => 'sometimes|numeric',
            'title' => 'sometimes|string|max:255',
            'language_iso' => 'sometimes|string|max:3',
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

        return response()->json(['message' => 'Study group and related data created successfully', 'data' => $result]);
    }

    public function updateStudyGroup(Request $request, $id)
    {
        // Валидация входящих данных
        $validated = $request->validate([
            'studyGroup' => 'required|array',
            'studyGroupInfo' => 'required|array',
        ]);

        try {
            // Вызов сервиса для обновления группы учебы и деталей и возвращение ответа сервиса
            return $this->studyGroupService->updateStudyGroupWithDetails($id, $validated['studyGroup'], $validated['studyGroupInfo']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Возврат ответа об ошибке, если группа учебы не найдена
            return response()->json([
                'success' => false,
                'message' => 'StudyGroup not found.',
            ], 404);
        } catch (\Exception $e) {
            // Возврат ответа об ошибке для любых других исключений
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function indexStudyGroup()
    {
        return $this->studyGroupService->getAllStudyGroups();
    }

    public function showStudyGroupById($id)
    {
        return $this->studyGroupService->getStudyGroupById($id);
    }

    public function deleteStudyGroup($id)
    {
        return $this->studyGroupService->deleteStudyGroup($id);
    }
}
