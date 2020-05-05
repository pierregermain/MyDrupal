<?php

namespace Drupal\trails\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
        $events[KernelEvents::REQUEST][] = array('saveTrail');
        return $events;
    }

    /**
     * Redirects the user when they're requesting our nearly blank page.
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     *   The response event.
     */
    public function saveTrail(GetResponseEvent $event) {

        // Grab the trail history from a variable
        //$trail = variable_get('trails_history', array());
        $trail = \Drupal::state()->get('trails.history', array());

        // Get Title

        $request = \Drupal::request();
        $route_match = \Drupal::routeMatch();
        $title = \Drupal::service('title_resolver')->getTitle($request, $route_match->getRouteObject());


        // Add current page to trail.
        $trail[] = array(
            'title' => strip_tags($title),
            'path' => \Drupal::request()->query->get('q') ,
            'timestamp' => REQUEST_TIME,
        );

        // Save the trail as a variable
        //variable_set('trails_history', $trail);
        \Drupal::state()->set('trails.history', $trail);

    }
}
