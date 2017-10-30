<?php

namespace Drupal\customentity;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Colorstructure entities.
 *
 * @ingroup customentity
 */
class ColorstructureListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Colorstructure ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\customentity\Entity\Colorstructure */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.colorstructure.edit_form',
      ['colorstructure' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
