<?php

namespace App\Providers;

use App\Models\Section;
use App\Models\BasicSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        Schema::defaultStringLength(191);

        $basic = BasicSetting::first();
        $section = Section::first();
        $meta = 1;

        View::share('site_title', $basic->title);
        View::share('basic', $basic);
        View::share('section', $section);
        View::share('meta', $meta);
    }
}
