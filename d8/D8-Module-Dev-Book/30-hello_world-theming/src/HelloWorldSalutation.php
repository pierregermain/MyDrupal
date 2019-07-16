<?php

namespace Drupal\hello_world;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Prepares the salutation to the world.
 */
class HelloWorldSalutation {

  use StringTranslationTrait;

  /**
   * @var ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * HelloWorldSalutation constructor.
   * @param ConfigFactoryInterface $config_factory
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
   */

  public function __construct(ConfigFactoryInterface $config_factory,
EventDispatcherInterface $eventDispatcher) {
    $this->configFactory = $config_factory;
    $this->eventDispatcher = $eventDispatcher;
  }

  /**
   * Returns the salutation
   */
  public function getSalutation() {

    $config = $this->configFactory->get('hello_world.custom_salutation');
    $salutation = $config->get('salutation');
    if ($salutation != ""){
      $event = new SalutationEvent();
      $event->setValue($salutation);
      $event = $this->eventDispatcher->dispatch(SalutationEvent::EVENT,$event);
      return $event->getValue();
    }
    return $this->t('Hello');

  }

  /**
   * Returns a the Salutation render array.
   */
  public function getSalutationComponent() {
    $render = [
      '#theme' => [
        'hello_world_salutation',
        ],
    ];
    $config = $this->configFactory->get('hello_world.custom_salutation');
    $salutation = $config->get('salutation');
    if ($salutation != "") {
      $render['#salutation'] = $salutation;
      $render['#overridden'] = TRUE;
      return $render;
    }
    $time = new \DateTime();
    $render['#target'] = $this->t('world');
    if ((int) $time->format('G') >= 06 && (int) $time->format('G') < 12) {
      $render['#salutation']['#markup'] = $this->t('Good morning');
      return $render;
    }
    else {
      $render['#salutation']['#markup'] = $this->t('Good evening');
      return $render;
    }
  }

}
