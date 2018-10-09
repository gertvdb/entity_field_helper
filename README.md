Description
-----------
This module provides a country manager enrich plugin to add extra country
specific information to the drupal country manager. It decorates the country
manager with 2 new methods to provide and search an enriched country list. 

Installation
------------
To install this module, do the following:

With composer:
1. ```composer require gertvdb/iso3166```

Examples
--------
You can find an example on how to enrich the CountryManager with your
own data below. 

#### Calling the CountryManager.
Since this module decorates the default Country Manager you can just call
or inject that service. When this module is enabled the country manager will 
provide 2 extra methods you can use.

``` 
  \Drupal::service('country_manager'); 
```


#### Method 1 : ``` getEnrichedList(); ```
This method will get you the enriched country data.

``` 
 $countyManager = \Drupal::service('country_manager');
 $list = $countryManager->getEnrichedList();
 
 // Outputs
 
 [
    'BE' => [
      'alpha2' => 'BE',
      'alpha3' => 'BEL',
      'numeric' => '056',
      ...
    ]
 ]
 
```

#### Method 2 : ```searchEnrichedList($key, $value);```
This method will help you search the enriched country data.
Note, this will always return an array since multiple countries
can match the search condition.


``` 
 $countyManager = \Drupal::service('country_manager');
 $result = $countryManager->searchEnrichedList('currency', 'EURO');
 
 // Outputs
 
 [
    'BE' => [
      'alpha2' => 'BE',
      'alpha3' => 'BEL',
      'numeric' => '056',
      'currency' => 'EURO'
    ],
    'NL' => [
      'alpha2' => 'NL',
      'alpha3' => 'NLD',
      'numeric' => '528',
      'currency' => 'EURO'
    ]
    ...
 ]
 
```

#### Add your own data to the CountryManager
The ISO3166CountryManager decorates the basic CountryManager service in Drupal and add's
the ability to enrich it with CountryManagerEnrichPlugins. By default this module
provides it's own enrich plugin to add ISO3166 data to the CountryManager. 


``` 
<?php

namespace Drupal\MY_MODULE\Plugin\CountryEnricherPlugin;

use Drupal\iso3166\Plugin\CountryEnricherPluginBase;

/**
 * Provide a President enricher.
 *
 * @CountryManagerEnrichPlugin(
 *   id = "president_enricher",
 *   label = @Translation("President enricher", context = "President"),
 *   enrich_key = "president"
 * )
 */
class ISO3166Enricher extends CountryEnricherPluginBase {
  
  /**
   * {@inheritdoc}
   */
   public function getEnrich() {
      return [
        'BE' => 'Charles Michel',
        'US' => 'Donald Thrump',
        ... 
      ];
   }
 
}

``` 
