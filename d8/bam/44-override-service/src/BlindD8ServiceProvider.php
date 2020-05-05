<?php

/**
 * @file
 * Contains \Drupal\blindd8\Blindd8ServiceProvider.
 */

namespace Drupal\blindd8;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Overrides our d8ing service.
 */
class BlindD8ServiceProvider extends ServiceProviderBase {
  public function alter(ContainerBuilder $container) {
    $definition = $container->getDefinition('blindd8.default');
    $definition->setClass('Drupal\blindd8\DefaultService2');
  }
}
