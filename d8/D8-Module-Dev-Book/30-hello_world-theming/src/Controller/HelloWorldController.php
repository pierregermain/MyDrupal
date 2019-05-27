<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\hello_world\HelloWorldSalutation;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

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
   * @return Response
   *   Return Hello string.
   */
  public function newResponse() {
    return new Response ('my text');
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

  /**
   * Hello.
   *
   * @return string
   *   Return Hello string.
   */
  public function helloWorldComponent() {
    return $this->salutation->getSalutationComponent();
  }

  /**
   * Redirect.
   *
   * @return RedirectResponse
   *   Return Redirect.
   */
  public function toHome() {
    return new RedirectResponse('/');
  }


}
