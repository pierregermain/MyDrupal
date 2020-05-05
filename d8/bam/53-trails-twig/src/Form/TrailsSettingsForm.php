<?php

namespace Drupal\trails\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\ImageToolkit\ImageToolkitManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configures image toolkit settings for this site.
 *
 * @internal
 */
class TrailsSettingsForm extends ConfigFormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'trails_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

      $max_in_settigns = $this->config('trails.settings')->get('max_in_settings');

      $form['max_in_settings'] = array(
          '#type' => 'select',
          '#title' => $this->t('Maximum number of items to display'),
          '#options' => array_combine(range(1, 200),range(1, 200)),
          '#default_value' => $max_in_settigns,
          '#description' => $this->t('This will set the maximum allowable number that can be displayed in a history block'),
          '#required' => TRUE,
      );



    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('trails.settings')
      ->set('max_in_settings', $form_state->getValue('max_in_settings'))
      ->save();


    parent::submitForm($form, $form_state);
  }

      /**
       * Gets the configuration names that will be editable.
       * {@inheritdoc}
       */
  protected function getEditableConfigNames() {
      return [
          'trails.settings',
      ];
  }


}
