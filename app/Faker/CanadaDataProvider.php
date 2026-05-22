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

  protected array $provinces = [
    'BC',
    'AB',
    'SK',
    'MB',
    'ON',
    'QC',
    'NB',
    'NS',
    'PE',
    'NL',
    'NU',
    'NW',
    'YU'
  ];

  public function province()
  {
    return static::randomElement(
      $this->provinces
    );
  }

  /**
   * NOTE: area codes and where they are used are accurate as of May 2026. No announced but as-yet-unimplemented changes were known at that time.
   */
  protected array $areaCodes = [
    'BC' => ['236', '250', '257', '604', '672', '778'],
    'AB' => ['368', '403', '587', '780', '825'],
    'SK' => ['306', '474', '639'],
    'MB' => ['204', '431', '584'],
    'ON' => ['226', '249', '289', '343', '365', '382', '416', '437', '519', '548', '613', '647', '683', '705', '742', '753', '807', '905', '942'],
    'QC' => ['263', '354', '367', '418', '438', '450', '468', '514', '579', '581', '819', '873'],
    'NB' => ['428', '506'],
    'NS' => ['782', '902'],
    'PE' => ['782', '902'],
    'NL' => ['709', '879'],
    'NU' => ['867'],
    'NT' => ['867'],
    'YT' => ['867'],
  ];


  /**
   * If no province or territory is specified, returns a random Canadian area code
   * If a province or territory is specified, returns an area code from that province or territory
   */
  public function areaCode(?string $province = null): string
  {
    if ($province !== null) {
      $province = strtoupper($province);
      if (!isset($this->areaCodes[$province])) {
        throw new \InvalidArgumentException("Unknown province/territory code: $province");
      }
      return static::randomElement($this->areaCodes[$province]);
    }

    // No province specified — pick from the entire country
    $all = array_unique(array_merge(...array_values($this->areaCodes)));
    return static::randomElement($all);
  }
}
