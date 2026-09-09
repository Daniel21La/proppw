<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Kepatuhan UU PDP No. 27/2022: Purge otomatis berkas KTP & SIM > 30 hari setelah unit kembali
Schedule::command('documents:purge-expired')->daily();
