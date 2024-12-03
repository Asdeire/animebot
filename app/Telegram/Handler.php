<?php

namespace App\Telegram;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Handler extends WebhookHandler
{
    public function start(): void
    {
        $keyboard = Keyboard::make()
            ->buttons([
                Button::make('Аніме')->action('anime'),
                Button::make('Улюблені')->action('favorites'),
                Button::make('Пошук')->action('search'),
                Button::make('Категорії')->action('categories')
            ]);

        $this->chat->message("Вітаю! Я бот для роботи з аніме.")
            ->keyboard($keyboard)
            ->send();
    }

    public function anime(): void
    {
        $animeList = app('App\Services\AnimeService')->getAllAnime();

        if (empty($animeList)) {
            $this->chat->message("Наразі список аніме порожній")->send();
            return;
        }

        $response = "Ось список доступного аніме:\n";
        foreach ($animeList as $anime) {
            $response .= "- {$anime['attributes']['titles']['en_jp']}\n";
        }

        $this->chat->message($response)->send();
    }

    public function animeDetails(int $id): void
    {
        $animeDetails = app('App\Services\AnimeService')->getAnimeDetails($id);

        if (!$animeDetails) {
            $this->chat->message("Не вдалося знайти інформацію про аніме з ID $id")->send();
            return;
        }

        $title = $animeDetails['attributes']['titles']['en_jp'] ?? 'Назва невідома';
        $description = $animeDetails['attributes']['synopsis'] ?? 'Опис відсутній';
        $rating = $animeDetails['attributes']['averageRating'] ?? 'Рейтинг відсутній';

        $keyboard = Keyboard::make()
            ->buttons([
                Button::make('Додати в улюблені')->action('addToFavorites')->param('id', $id)
            ]);

        $response = "Інформація про аніме '{$title}':\n";
        $response .= "Опис: {$description}\n";
        $response .= "Рейтинг: {$rating}";

        $this->chat->message($response)
            ->keyboard($keyboard)
            ->send();
    }

    public function favorites(): void
    {
        $favorites = Cache::get('favorites', []);
        if (empty($favorites)) {
            $this->chat->message("Список улюблених порожній. Додай аніме за допомогою кнопки 'Додати в улюблені' на сторінці деталей аніме")->send();
            return;
        }

        $response = "Твої улюблені аніме:\n";
        foreach ($favorites as $animeId => $rating) {
            $animeDetails = app('App\Services\AnimeService')->getAnimeDetails($animeId);
            $animeTitle = $animeDetails['attributes']['titles']['en_jp'] ?? 'Назва невідома';
            $response .= "- {$animeTitle}, Рейтинг: $rating\n";
        }

        $this->chat->message($response)->send();
    }

    public function search(string $query = null): void
    {
        if (!$query) {
            $this->chat->message("Будь ласка, введіть запит для пошуку аніме: /search 'назва'")->send();
            return;
        }

        $this->searchAnime($query);
    }

    public function searchAnime(string $query): void
    {
        $animeList = app('App\Services\AnimeService')->searchAnime($query);

        if (empty($animeList)) {
            $this->chat->message("Не знайдено аніме за запитом: $query")->send();
            return;
        }

        $response = "Результати пошуку для '$query':\n";
        foreach ($animeList as $anime) {
            $response .= "- {$anime['attributes']['titles']['en_jp']}\n";
        }

        $this->chat->message($response)->send();
    }

    public function addToFavorites(int $id, int $rating = 5): void
    {
        $animeDetails = app('App\Services\AnimeService')->getAnimeDetails($id);
        $animeTitle = $animeDetails['attributes']['titles']['en_jp'] ?? 'Назва невідома';

        $favorites = Cache::get('favorites', []);
        $favorites[$id] = $rating;
        Cache::put('favorites', $favorites);

        $this->chat->message("Аніме '{$animeTitle}' додано до улюблених з рейтингом $rating")->send();
    }

    public function removeFromFavorites(int $id): void
    {
        $favorites = Cache::get('favorites', []);
        if (isset($favorites[$id])) {
            $animeDetails = app('App\Services\AnimeService')->getAnimeDetails($id);
            $animeTitle = $animeDetails['attributes']['titles']['en_jp'] ?? 'Назва невідома';

            unset($favorites[$id]);
            Cache::put('favorites', $favorites);

            $this->chat->message("Аніме '{$animeTitle}' видалено з улюблених")->send();
        } else {
            $this->chat->message("Аніме з ID $id не знайдено в улюблених")->send();
        }
    }

    public function categories(): void
    {
        $categories = ['Action', 'Adventure', 'Comedy', 'Drama'];

        $keyboard = Keyboard::make()
            ->buttons(array_map(fn($category) => Button::make($category)->action('filterByCategory')->param('category', $category), $categories));

        $this->chat->message("Оберіть категорію:")
            ->keyboard($keyboard)
            ->send();
    }

    public function filterByCategory(string $category): void
    {
        $animeList = app('App\Services\AnimeService')->getAnimeByCategory($category);

        if (empty($animeList)) {
            $this->chat->message("Не знайдено аніме у категорії: $category")->send();
            return;
        }

        $response = "Аніме у категорії '{$category}':\n";
        foreach ($animeList as $anime) {
            $response .= "- {$anime['attributes']['titles']['en_jp']}\n";
        }

        $this->chat->message($response)->send();
    }
}
