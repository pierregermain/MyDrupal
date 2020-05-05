<?php

namespace Drupal\hello_world;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/** 
 * Prepares the salutation to the world. 
 */ 
class HelloWorldSalutation {

  use StringTranslationTrait;
  
  /**
   * Returns the salutation
   */
  public function getSalutation() {

    return $this->t('Hello');

  }
}
