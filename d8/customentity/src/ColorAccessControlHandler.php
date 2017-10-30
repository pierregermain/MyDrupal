<?php

namespace Drupal\customentity;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Color entity.
 *
 * @see \Drupal\customentity\Entity\Color.
 */
class ColorAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\customentity\Entity\ColorInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished color entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published color entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit color entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete color entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add color entities');
  }

}
