<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ExternalSpecialityService
{
    protected $client;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseUrl = env('COLLEGE_API_URL');
    }

    public function getSpecialityById($specialityId)
    {
        try {
            // Логируем URL перед выполнением запроса
            $url = $this->baseUrl . '/api/colleges/src1/specialities/' . $specialityId;
            Log::info("Requesting URL: " . $url);
    
            $response = $this->client->request('GET', $url);
    
            // Проверяем статус ответа
            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                return $data['title'] ?? 'Speciality not found';
            } else {
                // Логируем ошибку, если статус ответа не 200
                Log::error("API request failed with status code: " . $response->getStatusCode());
                return 'Speciality not found due to an error';
            }
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            // Логируем ошибку запроса к внешнему API
            Log::error("Error requesting external API: " . $e->getMessage());
            return 'Speciality not found';
        } catch (\Exception $e) {
            // Этот блок отлавливает остальные исключения, которые могут возникнуть
            Log::error("An unexpected error occurred: " . $e->getMessage());
            return 'An unexpected error occurred';
        }
    }
}