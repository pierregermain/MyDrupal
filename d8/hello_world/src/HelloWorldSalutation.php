<?php
// Service Classes always inside /src folder
namespace Drupal\hello_world;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Config\ConfigFactoryInterface;

/** 
 * Prepares the salutation to the world. 
 */ 
class HelloWorldSalutation { 
  use StringTranslationTrait;
  
  /**
  * @var \Drupal\Core\Config\ConfigFactoryInterface
  */
  protected $configFactory;
  
  /**
  * HelloWorldSalutation constructor.
  * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
  */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * Returns the salutation
   */
  public function getSalutation() {

    // With configuration object
    $config = $this->configFactory->get('hello_world.custom_salutation');
    $salutation = $config->get('salutation');
    if ($salutation != "") {
      return $salutation;
    }
    // If no configuration object was set this code will execute
    $time = new \DateTime();
    if ((int) $time->format('G') >= 06 && (int) $time->format('G') < 12) {
      return $this->t('Good morning world');
    }
    else {
      return $this->t('Good evening world');
    }
  }
}
