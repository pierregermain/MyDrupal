<?php

namespace Drupal\customentity\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Color entities.
 *
 * @ingroup customentity
 */
interface ColorInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Color name.
   *
   * @return string
   *   Name of the Color.
   */
  public function getName();

  /**
   * Sets the Color name.
   *
   * @param string $name
   *   The Color name.
   *
   * @return \Drupal\customentity\Entity\ColorInterface
   *   The called Color entity.
   */
  public function setName($name);

  /**
   * Gets the Color creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Color.
   */
  public function getCreatedTime();

  /**
   * Sets the Color creation timestamp.
   *
   * @param int $timestamp
   *   The Color creation timestamp.
   *
   * @return \Drupal\customentity\Entity\ColorInterface
   *   The called Color entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Color published status indicator.
   *
   * Unpublished Color are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Color is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Color.
   *
   * @param bool $published
   *   TRUE to set this Color to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\customentity\Entity\ColorInterface
   *   The called Color entity.
   */
  public function setPublished($published);

}
