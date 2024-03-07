<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CurriculumService;
use App\Models\EduBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CurriculumController extends Controller
{
    protected $curriculumService;

    public function __construct(CurriculumService $curriculumService)
    {
        $this->curriculumService = $curriculumService;
    }

    public function createCurriculum(Request $request)
    {
        // Получаем все query параметры
        $queryParams = $request->query();

        // Правила валидации
        $rules = [
            'title' => 'required|string',
            'year' => 'required|string',
            'college_id' => 'required|integer',
            'study_group_ids' => 'nullable|string', // строка с ID, разделенными запятой
        ];

        // Создаем валидатор
        $validator = Validator::make($queryParams, $rules);

        // Проверяем валидацию
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Валидация прошла успешно
        $validated = $validator->validated();

        // Преобразование строки study_group_ids в массив, если параметр присутствует
        $validated['study_group_ids'] = !empty($validated['study_group_ids']) ? explode(',', $validated['study_group_ids']) : [];
        $validated['study_group_ids'] = array_filter(array_map('intval', $validated['study_group_ids']));

        try {
            $curriculum = $this->curriculumService->createCurriculumAndStudyGroups($validated);
            return response()->json($curriculum, 201);
        } catch (\Exception $e) {
            // Обработка исключения
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(Request $request)
    {
        // Добавляем 'year' в список параметров, которые мы хотим извлечь из запроса
        $filters = $request->only(['curriculum_id', 'college_id', 'year']);
    
        try {
            $curriculums = $this->curriculumService->getCurriculumWithFilters($filters);
            return response()->json($curriculums);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400); // Используем код 400 для всех ошибок, если не указан другой
        }
    }

    public function showDelete(Request $request)
    {
        // Добавляем 'year' в список параметров, которые мы хотим извлечь из запроса
        $filters = $request->only(['curriculum_id', 'college_id', 'year']);
    
        try {
            $curriculums = $this->curriculumService->getCurriculumWithFiltersDelete($filters);
            return response()->json($curriculums);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400); // Используем код 400 для всех ошибок, если не указан другой
        }
    }


    public function changeStatusDelete(Request $request)
    {
        $curriculumId = $request->query('curriculum_id');
        $status = $request->query('status_delete', true); // Значение по умолчанию false, если не предоставлено
        $status = filter_var($status, FILTER_VALIDATE_BOOLEAN); // Преобразование в boolean

        if (!$curriculumId) {
            return response()->json(['success' => false, 'message' => 'Curriculum ID is required.'], 400);
        }

        $result = $this->curriculumService->changeStatusDelete($curriculumId, $status);
        return response()->json($result);
    }

    public function indexStudyGroupForAddRup(Request $request)
    {
        // Собираем все фильтры из запроса
        $filters = $request->only(['college_id', 'edu_base_id', 'speciality_id']);
    
        // Проверяем, есть ли хотя бы один фильтр
        if (empty($filters['college_id']) && empty($filters['edu_base_id']) && empty($filters['speciality_id'])) {
            return response()->json([]); // Возвращаем пустой ответ, если нет фильтров
        }
    
        $result = $this->curriculumService->getAllStudyGroups($filters);
    
        return response()->json($result);
    }
    public function showEduBase()
    {
        $eduBases = $this->curriculumService->getAllEduBases();
        return response()->json($eduBases);
    }

    public function updateCurriculum(Request $request)
{
    // Получаем curriculum_id из query параметра
    $curriculumId = $request->query('curriculum_id');

    // Получаем остальные параметры из запроса
    $queryParams = $request->query();

    // Правила валидации
    $rules = [
        'curriculum_id' => 'required|integer',
        'title' => 'required|string',
        'year' => 'required|string',
        'college_id' => 'required|integer',
        'study_group_ids' => 'nullable|string', // строка с ID, разделенными запятой
    ];

    // Создаем валидатор
    $validator = Validator::make($queryParams, $rules);

    // Проверяем валидацию
    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    // Валидация прошла успешно
    $validated = $validator->validated();

    // Преобразование строки study_group_ids в массив, если параметр присутствует
    $validated['study_group_ids'] = !empty($validated['study_group_ids']) ? explode(',', $validated['study_group_ids']) : [];
    $validated['study_group_ids'] = array_filter(array_map('intval', $validated['study_group_ids']));

    try {
        $curriculum = $this->curriculumService->updateCurriculumAndStudyGroups($curriculumId, $validated);
        return response()->json($curriculum, 200);
    } catch (\Exception $e) {
        // Обработка исключения
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
    
}
