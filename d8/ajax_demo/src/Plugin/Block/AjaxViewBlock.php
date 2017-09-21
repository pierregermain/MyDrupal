<?php
 
namespace Drupal\ajax_demo\Plugin\Block;
 
use Drupal\Core\Block\BlockBase;
 
/**
 * Provides a 'AjaxViewBlock' block.
 *
 * @Block(
 *  id = "ajax_view_block",
 *  admin_label = @Translation("Ajax view block"),
 * )
 */
class AjaxViewBlock extends BlockBase {
 
  /**
   * {@inheritdoc}
   */
  public function build() {
 
    $build = [];
 
    $build['ajax_view_block'] = [
      '#theme' => 'ajax_demo',
      '#attached' => ['library' => 'ajax_demo/ajax'],
    ];
 
    return $build;
  }
 
}
