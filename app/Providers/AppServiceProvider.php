<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Faker\Generator;
use App\Faker\DevGigsProvider;
use App\Faker\CanadaDataProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    if ($this->app->environment('local', 'testing')) {
      $this->app->make(Generator::class)
        ->addProvider(
          new DevGigsProvider(
            $this->app->make(Generator::class)
          )
        );

      $this->app->make(Generator::class)
        ->addProvider(
          new CanadaDataProvider(
            $this->app->make(Generator::class)
          )
        );
    }

    if (config('app.env') === 'production') {
      \Illuminate\Support\Facades\URL::forceScheme('https');
    }
  }
}
