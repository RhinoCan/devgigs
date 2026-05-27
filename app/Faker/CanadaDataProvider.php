<?php

namespace App\Faker;

use Faker\Provider\Base;

class CanadaDataProvider extends Base
{
  protected array $locations = [
    'Vancouver, BC',
    'Victoria, BC',
    'Calgary, AB',
    'Edmonton, AB',
    'Regina, SK',
    'Saskatoon, SK',
    'Winnipeg, MB',
    'Thompson, MB',
    'Toronto, ON',
    'Ottawa, ON',
    'Montreal, QC',
    'Quebec, QC',
    'Saint John, NB',
    'Moncton, NB',
    'Halifax, NS',
    'Digby, NS',
    'Charlottetown, PE',
    'Summerside, PE',
    "St. John's, NL",
    'Goose Bay, NL'
  ];

  public function canadianLocation()
  {
    return static::randomElement(
      $this->locations
    );
  }
}
