<?php

namespace Drupal\hello_world\Logger;

use Drupal\Core\Logger\RfcLoggerTrait;
use Psr\Log\LoggerInterface;

//use Drupal\Component\Render\FormattableMarkup;
//use Drupal\Core\Config\ConfigFactoryInterface;
//use Drupal\Core\Logger\LogMessageParserInterface;
//use Drupal\Core\Logger\RfcLogLevel;
//use Drupal\Core\Session\AccountProxyInterface;
/**
 * A logger that sends an email when the log type is error.
 */
class MailLogger implements LoggerInterface {
  use RfcLoggerTrait;
  /**
   * {@inheritdoc}
   */
  public function log($level, $message, array $context = array()) {
    // Log our message to our logging system
  }
}