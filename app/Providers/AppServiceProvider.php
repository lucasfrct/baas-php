<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('currence_cents', function ($cents) {
            return "<?php echo number_format(($cents/100),2); ?>";
        });

        Blade::directive('money', function ($cents) {
            return "<?php echo Cashier::formatAmount(($cents/100), 'gbp'); ?>";
        });
    }
}
