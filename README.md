# Entity Field Helper

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/gertvdb/entity_field_helper/blob/8.x-1.x/LICENSE.md)
[![Travis](https://img.shields.io/travis/gertvdb/entity_field_helper.svg)](https://travis-ci.org/gertvdb/entity_field_helper) 
[![Coverage Status](https://coveralls.io/repos/github/gertvdb/entity_field_helper/badge.svg?branch=8.x-1.x)](https://coveralls.io/github/gertvdb/entity_field_helper?branch=8.x-1.x)
[![Packagist](https://img.shields.io/packagist/v/gertvdb/entity_field_helper.svg)](https://packagist.org/packages/gertvdb/entity_field_helper)

Description
-----------
This module provides a helper class for getting values from entities.
The main principle of the module is that the value functions will always 
return NULL or the actual value when found. This makes it easy to handle
printing and sending data to the templates. 

Installation
------------
To install this module, do the following:

With composer:
1. ```composer require gertvdb/entity_field_helper```

Examples
--------
You can find an example on how to use the entity field helper below. 
The entity field helper classes are plugins so other modules can provide
extra helpers.  

#### Getting a Known helper (Provided by module).
The entity field helper provides several plugin for most of the 
field types in Drupal Core. The plugin manager contains shortcuts to get
those helpers.

##### Basic.

``` 
  /** @var \Drupal\entity_field_helper\Plugin\EntityFieldHelperManager $pluginManager */
  $pluginManager = \Drupal::service('plugin.manager.entity_field_helper');
  
  // Get the node object.
  $node = Node::load(1);
  
  $processedTextHelper = $pluginManager->processedTextHelper();
  $processedTextHelper->getValue($node, 'body');
  
```
##### Advanced.

``` 
  /** @var \Drupal\entity_field_helper\Plugin\EntityFieldHelperManager $pluginManager */
  $pluginManager = \Drupal::service('plugin.manager.entity_field_helper');
  
  // Get the node object.
  $node = Node::load(1);
  
  $imageHelper = $pluginManager->imageHelper();
  $visuals = $imageHelper->getValues($node, 'field_visuals');
  
  $images = [];
  $imageStyle = ImageStyle::load('slider');
  
  foreach ($visuals as $visual) {
      $image = $imageHelper->getImage($visual);
      $imageWidth = $image->getWidth();
      if ($imageWidth > 1000) {
        $images[] = $imageHelper->getImageStyleUrl($visual, $imageStyle);
      }
  }
  
```

##### Custom helper.

``` 
namespace Drupal\entity_field_helper\Plugin\EntityFieldHelper;

use Drupal\entity_field_helper\Plugin\EntityFieldHelperBase;

/**
 * Provides a General Entity Field Helper.
 *
 * @EntityFieldHelper(
 *   id = "my_custom_helper",
 *   name = "My Custom Helper",
 * )
 */
final class MyCustomHelper extends EntityFieldHelperBase {}
``` 

``` 
  /** @var \Drupal\entity_field_helper\Plugin\EntityFieldHelperManager $pluginManager */
  $pluginManager = \Drupal::service('plugin.manager.entity_field_helper');
  
  $myCustomHelper = $pluginManager->createInstance('my_custom_helper');
```
