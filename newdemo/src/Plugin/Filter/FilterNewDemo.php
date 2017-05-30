<?php

/**
 * @file
 * Contains Drupal\newdemo\Plugin\Filter\FilterNewDemo
 */

namespace Drupal\newdemo\Plugin\Filter;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;

/**
 * Provides a filter to demo
 *
 * @Filter (
 *   id = "filter_newdemo",
 *   title = @Translation("Tec Demo Filter"),
 *   description = @Translation("Text format converts President to name"),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE,
 * )
 */

class FilterNewdemo extends FilterBase {
  /**
   * {@inheritdoc}
   */
  public function process ($text, $langcode) {
    $replace = "Juanjo Medina Azara de la Mora";
    $new_text = str_ireplace('[[PRESIDENT]]', $replace, $text);

    $result = new FilterProcessResult($new_text);
    return $result;
  }
}


