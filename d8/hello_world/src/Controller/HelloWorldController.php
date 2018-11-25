<?php

namespace Drupal\hello_world\Controller;
use Drupal\Core\Controller\ControllerBase; 

/**
 * Controller for the salutation message.
 *
 */
class HelloWorldController extends ControllerBase { 
	/**
	 * Hello World.
   * @return string 
   */
	public function helloWorld() { 
		return [
			'#markup' => $this->t('Hello World') 
		]; 
  }

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
}
