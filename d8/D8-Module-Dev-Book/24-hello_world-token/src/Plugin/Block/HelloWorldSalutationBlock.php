<?php

namespace Drupal\hello_world\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\hello_world\HelloWorldSalutation as HelloWorldSalutationService;

/**
 * Hello World Salutation block.
 *
 * @Block(
 *  id = "hello_world_salutation_block",
 *  admin_label = @Translation("Hello world salutation"),
 * )
 */

class HelloWorldSalutationBlock extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * Drupal\hello_world\HelloWorldSalutation definition.
   *
   * @var \Drupal\hello_world\HelloWorldSalutation
   */
  protected $salutation;

  /**
   * Construct.
   *
   * @param array $configuration
   * A configuration array containing information about the plugin instance.
   *
   * @param string $plugin_id
   * The plugin_id for the plugin instance.
   *
   * @param string $plugin_definition
   * The plugin implementation definition.
   *
   * @param \Drupal\hello_world\HelloWorldSalutation $salutation
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, HelloWorldSalutationService $salutation) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->salutation = $salutation;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array
  $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('hello_world.salutation')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'enabled' => 0,
    ];
  }

  /*
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();

    $form['enabled'] = array (
      '#type' => 'checkbox',
      '#title' => t('Enabled'),
      '#description' => t('Check this box to show route links'),
      '#default_value' => $config['enabled'],
    );

    return $form;
  }

  /*
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['enabled'] = $form_state->getValue('enabled');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();

    if($config['enabled'] == 0){
      return [
        '#markup' => $this->salutation->getSalutation(),
      ];
    }

    // Link from Link class

    $url = Url::fromRoute('hello_world.greeting_form');
    $link = Link::fromTextAndUrl('Config Page', $url);
    $link = $link->toString();

    // Link from service

    $url2 = Url::fromRoute('hello_world.hello_world');
    $link2 = \Drupal::service('link_generator')->generate('Hello World Link', $url2);

    return [
      '#markup' => $this->t('Link 1: '. $link.'<br>'.
                                   'Link 2: '. $link2),
    ];

  }
}
