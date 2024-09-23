<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


// Artisan::command('app:print-reminders', function () {
//     // Lógica para imprimir lembretes
//     $this->info('Imprimindo lembretes...');
//     // Adicione sua lógica aqui
// })->purpose('Imprime lembretes que estão vencidos');

Schedule::command('app:print-reminders')->everyMinute();
