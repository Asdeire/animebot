<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('tester', function () {
    /** @var \DefStudio\Telegraph\Models\TelegraphBot $bot */
    $bot = \DefStudio\Telegraph\Models\TelegraphBot::find(1);

    $bot->registerCommands([
        'anime' => 'Отримати список доступного аніме',
        'favorites' => 'Переглянути список улюблених аніме',
        'search' => 'Пошук аніме за назвою',
        'categories' => 'Переглянути доступні категорії аніме',
        'animeDetails' => 'Отримати деталі аніме за ID',
        'addToFavorites' => 'Додати аніме в улюблені',
        'removeFromFavorites' => 'Видалити аніме з улюблених',
        'filterByCategory' => 'Фільтрувати аніме за категорією'
    ])->send();
});
