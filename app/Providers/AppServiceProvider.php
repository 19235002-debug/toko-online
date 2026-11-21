<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (file_exists(app_path('Helpers/helpers.php'))) {
            require_once app_path('Helpers/helpers.php');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Kunci zona waktu ke Asia/Jakarta (WIB / UTC+7) dan set locale Indonesia
        $tz = config('app.timezone', 'Asia/Jakarta');
        date_default_timezone_set($tz);
        \Carbon\Carbon::setLocale('id');
        \Illuminate\Support\Carbon::setLocale('id');

        // Blade Directives untuk tombol aksi seragam
        Blade::directive('btnEdit', function ($expression) {
            return "<?php echo \App\Helpers\ActionHelper::edit($expression); ?>";
        });

        Blade::directive('btnDelete', function ($expression) {
            return "<?php echo \App\Helpers\ActionHelper::delete($expression); ?>";
        });

        Blade::directive('btnActions', function ($expression) {
            return "<?php echo \App\Helpers\ActionHelper::actions($expression); ?>";
        });

        Blade::directive('btnCreate', function ($expression) {
            return "<?php echo \App\Helpers\ActionHelper::create($expression); ?>";
        });

        Blade::directive('btnPrint', function ($expression) {
            return "<?php echo \App\Helpers\ActionHelper::print($expression); ?>";
        });

        Blade::directive('btnBack', function ($expression) {
            return "<?php echo \App\Helpers\ActionHelper::back($expression); ?>";
        });

        Blade::directive('btnSave', function ($expression) {
            return "<?php echo \App\Helpers\ActionHelper::save($expression); ?>";
        });

        Blade::directive('btnReset', function ($expression) {
            return "<?php echo \App\Helpers\ActionHelper::reset($expression); ?>";
        });

        // Share store settings ke seluruh views (menggunakan cache singleton)
        try {
            \Illuminate\Support\Facades\View::share('storeSetting', \App\Models\Setting::getSettings());
        } catch (\Throwable $e) {
            // Abaikan jika database belum terhubung
        }
    }
}
