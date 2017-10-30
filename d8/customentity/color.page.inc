<?php

/**
 * @file
 * Contains color.page.inc.
 *
 * Page callback for Color entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Color templates.
 *
 * Default template: color.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_color(array &$variables) {
  // Fetch Color Entity Object.
  $color = $variables['elements']['#color'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
