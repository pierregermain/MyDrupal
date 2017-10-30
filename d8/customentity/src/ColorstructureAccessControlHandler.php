<?php

namespace Drupal\customentity;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Colorstructure entity.
 *
 * @see \Drupal\customentity\Entity\Colorstructure.
 */
class ColorstructureAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\customentity\Entity\ColorstructureInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished colorstructure entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published colorstructure entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit colorstructure entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete colorstructure entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add colorstructure entities');
  }

}
