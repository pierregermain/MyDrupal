<?php

namespace Drupal\customentity\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Colorstructure entities.
 *
 * @ingroup customentity
 */
interface ColorstructureInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Colorstructure name.
   *
   * @return string
   *   Name of the Colorstructure.
   */
  public function getName();

  /**
   * Sets the Colorstructure name.
   *
   * @param string $name
   *   The Colorstructure name.
   *
   * @return \Drupal\customentity\Entity\ColorstructureInterface
   *   The called Colorstructure entity.
   */
  public function setName($name);

  /**
   * Gets the Colorstructure creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Colorstructure.
   */
  public function getCreatedTime();

  /**
   * Sets the Colorstructure creation timestamp.
   *
   * @param int $timestamp
   *   The Colorstructure creation timestamp.
   *
   * @return \Drupal\customentity\Entity\ColorstructureInterface
   *   The called Colorstructure entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Colorstructure published status indicator.
   *
   * Unpublished Colorstructure are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Colorstructure is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Colorstructure.
   *
   * @param bool $published
   *   TRUE to set this Colorstructure to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\customentity\Entity\ColorstructureInterface
   *   The called Colorstructure entity.
   */
  public function setPublished($published);

}
