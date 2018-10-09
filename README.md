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
  /** @var \Drupal\entity_field_helper\Plugin\EntityFieldHelperManager $pluginManager */
  $pluginManager = \Drupal::service('plugin.manager.entity_field_helper');
  
  $myCustomHelper = $pluginManager->createInstance('my_custom_helper');
```