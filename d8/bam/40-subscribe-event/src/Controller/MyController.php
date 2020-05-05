<?php

namespace Drupal\blindd8\Controller;

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

    /**
     * Provides a page that we can experiment with.
     *
     * @return array
     *   A render array as expected by drupal_render().
     */
    public function content()
    {
        // Get our username
        $account = \Drupal::currentUser(); // Another way of using \Drupal::service('current_user');
        $name = $account->getAccountName();

        // Generate a UUID
        $uuid_generator = \Drupal::service('uuid');
        $uuid = $uuid_generator->generate();

        // Send it forth!
        $output = array(
            '#markup' => $this->t('Hey, @name, here\'s a unique ID for you: @uuid', array('@name' => $name, '@uuid' => $uuid)),
        );
        return $output;
    }

    /**
     * Provides a page that we can experiment with.
     *
     * @return array
     *   A render array as expected by drupal_render().
     */
    public function tag()
    {
        $myservice = \Drupal::service('blindd8.default');
        $tagline = $myservice->getTagline();

        // Send it forth!
        $output = array(
            '#markup' => $this->t('Hey, this is @tagline', array('@tagline' => $tagline)),
        );
        return $output;
    }
}
