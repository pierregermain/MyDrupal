<?php

namespace Drupal\trails\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

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

      // Create list of previous paths.

      // Grab the trail history from a variable
      $trail = \Drupal::state()->get('trails.history', array());

      // Flip the saved array to show newest pages first.
      $reverse_trail = array_reverse($trail);

      // Grab the number of items to display
      //$num_items = variable_get('trails_block_num', '5');
      //$num_items = \Drupal::state()->get('trails.num_items', 5);
      $num_items = $this->configuration['num_to_show'];

      // Output the latest items as a list
      $output = ''; // Initialize variable, this was added after the video was created.
      for ($i = 0; $i < $num_items; $i++) {
          if ($item = $reverse_trail[$i]) {
              //$output .= '<li>' . l($item['title'], $item['path']) . ' - ' . format_interval(REQUEST_TIME - $item['timestamp']) . ' ' . t('ago') . '</li>';
              $time =  \Drupal::service('date.formatter')->formatInterval(\Drupal::time()->getRequestTime() - $item['timestamp']);
              $output .= '<li>' . $item['title'] .' - ' . $item['path'] .  $time . ' ' .  t('ago').'</li>';
          }
      }
      if (isset($output)) {
          $output = '
            <p>' . t('Below are the last @num pages you have visited.', array('@num' => $num_items)) . '</p>
            <ul>' . $output . '</ul>
          ';
      }

    return ['#markup' => $output];
  }

  public function buildConfigurationForm(array $form, FormStateInterface $form_state)
  {

      $form = parent::buildConfigurationForm($form, $form_state);

      // Get the maximum allowed value from the configuration form.
      $max_in_settings = \Drupal::config('trails.settings')->get('max_in_settings');

      //var_dump($this->configuration['num_to_show']);
      //var_dump($this);
      //var_dump($form);
      //var_dump($form_state);
      //die();

      // Add a select box of numbers form 1 to $max_to_display.
      $form['trails_block_num'] = array(
          '#type' => 'select',
          '#title' => t('Number of items to show'),
          '#default_value' => $this->configuration['num_to_show'] ?: 5,
          '#options' => array_combine(range(1, $max_in_settings),range(1, $max_in_settings)),
      );

      return $form;
  }

    /**
     * {@inheritdoc}
     */
    public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
        $this->configuration['num_to_show'] = $form_state->getValue('trails_block_num');
    }


}
