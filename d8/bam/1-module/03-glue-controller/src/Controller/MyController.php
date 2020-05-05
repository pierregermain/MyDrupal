<?php

namespace Drupal\glue\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class MyController.
 */
class MyController extends ControllerBase {

    /**
     * Hello.
     *
     * @param $name
     * @return array
     *   Return Hello string.
     */
  public function hello($name) {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Your input is: <b>'.$name.'</b>'),
    ];
  }

}
