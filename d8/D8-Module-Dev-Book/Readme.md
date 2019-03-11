# Drupal 8 Module Development Notes
 - T3 Logging and Mailing
 
[TOC GENERATE](https://magnetikonline.github.io/markdown-toc-generate/)

(...)

# Introduction

 - We will take a look how logging in D8 works expanding the hello_world module.
 - We will look at Mail API using PHP mail and creating your own email system and using  ? and/or ? creating an mail plugin.
 - We will see the D8 token system so that your mails are more dynamic.

# Logging

 - Logging in Drupal 8 is registered in the `watchdog` DB table.
 - UI's `/admin/reports/dblog` equals to the `watchdog` table.
 - With the `Syslog` core module we can complement/replace the logging with the one of the server.
 - We still use the `watchdog()` function of D7, but all the code has been ported to PS-3 so other implementation can also be used.

## The Drupal 8 Logging theory

We have 3 keyplayers in Drupal 8 that work with the logging framework

1. `LoggerChannel` which represents a category of logged messages. They are objects that contact the `LoggerChannelFactory`.
2. `LoggerChannelFactory` is a service used to be in touch with the logging framework. 

Example:
```php
\Drupal::logger('hello_world')->error('This is the error message');// hello_world is the category
// this will be translated to
\Drupal::service('logger.factory')->get('hello_world')->error('This is the error message');
```

3. `LoggerInterface` implementation with the `RfcLoggerTrait`. It takes services tagged with the `logger` tag, at sends them to the `LoggerChannelFactory`.

## Your own logger channel

# Mailing

## Tokens