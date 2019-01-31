<?php

namespace Drupal\blindd8\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class Subscriber.
 */
class Subscriber implements EventSubscriberInterface {


  /**
   * Constructs a new Subscriber object.
   */
  public function __construct() {

  }

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events['kernel.response'] = ['kernel_response'];
    return $events;
  }
  /**
   * This method is called whenever the kernel.response event is
   * dispatched.
   *
   * @param GetResponseEvent $event
   */
    public function kernel_response(Event $event) {

        $route = \Drupal::routeMatch()->getRouteName();
        if ($route == 'system.404') {
            $response = $event->getResponse();
            $response->setContent('Blind date, get it?!');
            $response->setStatusCode('404');
        }
    }
}
