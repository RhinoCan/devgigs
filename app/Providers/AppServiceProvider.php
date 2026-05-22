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
    // functions techSkills and techSkillsCsv displays 1 to 4 Laravel/PHP skills
    // function techTitles displays a random IT job title
    $this->app->make(Generator::class)
      ->addProvider(
        new DevGigsProvider(
          $this->app->make(Generator::class)
        )
      );

    // Displays a Canadian city for use in Gigs portion of the app
    $this->app->make(Generator::class)
      ->addProvider(
        new CanadaDataProvider(
          $this->app->make(Generator::class)
        )
      );
  }
}
