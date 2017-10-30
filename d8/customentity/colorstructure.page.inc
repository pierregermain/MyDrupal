<?php

/**
 * @file
 * Contains colorstructure.page.inc.
 *
 * Page callback for Colorstructure entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Colorstructure templates.
 *
 * Default template: colorstructure.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_colorstructure(array &$variables) {
  // Fetch Colorstructure Entity Object.
  $colorstructure = $variables['elements']['#colorstructure'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
