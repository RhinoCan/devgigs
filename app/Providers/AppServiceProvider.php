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
/*
| -------------------------------------------------------------------------- |
| AppServiceProvider HTTPS Enforcement                                       |
| -------------------------------------------------------------------------- |
|                                                                            |
| No automated test is provided for the URL::forceScheme('https') call in    |
| AppServiceProvider::boot().                                                |
|                                                                            |
| The code contains no application-specific business logic and merely        |
| delegates to Laravel's framework functionality. Testing it would require   |
| coupling the test to Laravel's bootstrapping internals, producing a        |
| brittle test with little additional confidence.                            |
|                                                                            |
| The behaviour is instead verified through application configuration and    |
| manual deployment checks in the production environment.                    |
| -------------------------------------------------------------------------- |
*/
    if (config('app.env') === 'production') {
      \Illuminate\Support\Facades\URL::forceScheme('https');
    }

    \Illuminate\Pagination\Paginator::defaultView('pagination::tailwind');
  }
}
