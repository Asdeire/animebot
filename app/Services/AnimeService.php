<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class AnimeService
{
    protected const BASE_URL = 'https://kitsu.io/api/edge';

    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => self::BASE_URL,
            'timeout' => 10.0, // Час очікування відповіді
        ]);
    }

    /**
     * Отримати список усіх аніме.
     *
     * @return array
     */
    public function getAllAnime(): array
    {
        try {
            $response = $this->client->get('https://kitsu.io/api/edge/anime');
            $body = $this->decodeResponse($response);
            return $body['data'] ?? [];
        } catch (\Exception $e) {
            Log::error('Error fetching anime list: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Шукати аніме за текстовим запитом.
     *
     * @param string $query
     * @return array
     */
    public function searchAnime(string $query): array
    {
        try {
            $response = $this->client->get('https://kitsu.io/api/edge/anime', [
                'query' => [
                    'filter[text]' => $query,
                ],
            ]);
            $body = $this->decodeResponse($response);
            return $body['data'] ?? [];
        } catch (\Exception $e) {
            Log::error('Error searching anime: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Отримати деталі про конкретне аніме за ID.
     *
     * @param int $id
     * @return array|null
     */
    public function getAnimeDetails(int $id): ?array
    {
        try {
            $response = $this->client->get("https://kitsu.io/api/edge/anime/$id");
            $body = $this->decodeResponse($response);
            return $body['data'] ?? null;
        } catch (\Exception $e) {
            Log::error('Error fetching anime details: ' . $e->getMessage());
            return null;
        }
    }

    public function getAnimeByCategory(string $category): array
    {
        try {
            $response = $this->client->get('https://kitsu.io/api/edge/anime', [
                'query' => [
                    'filter[categories]' => $category,
                ],
            ]);
            $body = $this->decodeResponse($response);
            return $body['data'] ?? [];
        } catch (\Exception $e) {
            Log::error('Error fetching anime by category: ' . $e->getMessage());
            return [];
        }
    }


    /**
     * Розшифровка відповіді API.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return array
     */
    private function decodeResponse($response): array
    {
        $contents = $response->getBody()->getContents();
        if (empty($contents)) {
            Log::error('Empty response body');
            return [];
        }

        $data = json_decode($contents, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON decode error: ' . json_last_error_msg());
            return [];
        }

        return $data ?? [];
    }
}
