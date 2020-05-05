<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\hello_world\HelloWorldSalutation;

/**
 * Class DefaultController.
 */
class HelloWorldController extends ControllerBase {

    /**
     * @var \Drupal\hello_world\HelloWorldSalutation
     */
    protected $salutation;


    /**
     * HelloWorldController constructor.
     *
     * @param \Drupal\hello_world\HelloWorldSalutation $salutation
     */
    public function __construct(HelloWorldSalutation $salutation) {
        $this->salutation = $salutation;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container) {
        return new static(
            $container->get('hello_world.salutation')
        );
    }

  /**
   * Hello.
   *
   * @return string
   *   Return Hello string.
   */
  public function helloWorld() {
    return [
      '#type' => 'markup',
      '#markup' => $this->salutation->getSalutation(),
    ];
  }

}
