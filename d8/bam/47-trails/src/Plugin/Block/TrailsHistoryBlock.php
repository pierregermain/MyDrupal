<?php

namespace Drupal\trails\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Trails History' block.
 *
 * @Block(
 *   id = "trails_history_block",
 *   admin_label = @Translation("Trails History")
 * )
 */
class TrailsHistoryBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return ['label_display' => FALSE];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return ['#markup' => '<span>' . $this->t('Custom Block Changed') . '</span>'];
  }

}
