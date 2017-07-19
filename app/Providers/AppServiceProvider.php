<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        //валидация telnumber
        Validator::extend('telnumber', function ($attribute, $value, $parameters, $validator) {
            if (($value >= 380390000000 && $value <= 380399999999) || ($value >= 380670000000 && $value <= 380679999999) || ($value >= 380680000000 && $value <= 380689999999) || ($value >= 380960000000 && $value <= 380969999999) || ($value >= 380970000000 && $value <= 380979999999) || ($value >= 380980000000 && $value <= 380989999999) || ($value >= 380500000000 && $value <= 380509999999) || ($value >= 380660000000 && $value <= 380669999999) || ($value >= 380950000000 && $value <= 380959999999) || ($value >= 380990000000 && $value <= 380999999999) || ($value >= 380630000000 && $value <= 380639999999) || ($value >= 380930000000 && $value <= 380939999999) || ($value >= 380910000000 && $value <= 380919999999) || ($value >= 380920000000 && $value <= 380929999999) || ($value >= 380940000000 && $value <= 380949999999)) {
                return $value;
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
