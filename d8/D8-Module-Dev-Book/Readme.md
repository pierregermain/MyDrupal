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

## Our own logger channel

Example: `21-hello_world-logger`

We add the following definition to our *.services.yml file
```
  hello_world.logger.channel.hello_world:
    parent: logger.channel_base
    arguments: ['hello_world']
```

The parent key means that our service will inherit the definition from another service.
The arguments key does not have the @ sign: So now we just pass a single string (with the @ sign we pass a service name).

Requesting this service will under the hood do the following task:
`\Drupal::service('logger.factory')->get('hello_world');`

### Our own logger

Let's imagine we want to also send an email when we log an message. We will only cover the architecture for that.

Steps:


### Our own logger

Let's imagine we want to also send an email when we log an message. We will only cover the architecture for that.

Steps:
1. Create `MailLogger.php` that is a `LoggerInterface` class in `/src/Logger` folder. We PSR-3 Psr\Log\LoggerInterface;
2. We will need to implement in the future the log method. That's basically all!
3. We register the class as a tagged service so that `LoggingChannelFactory` picks it up ans passes it to the logging channel.
We add the following to the *.services.yml file

``` 
  hello_world.logger.hello_world:
    class: Drupal\hello_world\Logger\MailLogger
    tags:
      - { name: logger }
```

Clearing the caches would enable our logger.

#### Logging in Hello World

 - Let's add some logging to our module. 
 - Let's log an info message when an admin changes the greeting message 
 - So we modify the `/src/Form/SalutationConfigurationForm` injecting the service to our form.

Note:
 - `FormBase` implements `ContainerInjectionInteface`.
 - But we need the `create()` method to add our service and store it in a property (created with the constructor).
 - So we add the following:
   - protected $logger;
   - __constructor(...)
   - create (...)
 - We log the message in the submit() method

# Mailing

## Introduction

We will see how to send emails programmatically. The default system uses Php mail. After using the default we will see how to use our own system.

Sending emails is a 2 part job:
 1. Define a *template* for the email in our module: This is a procedural data wrapper to the email you want to send using the *hook_mail()* with the *key* and *message ID* arguments.
 2. Use the Drupal Mail Manager to send an email using one of the defined *templates*. The default "plugin" is `PhpMail` that uses the `mail()` function.
 
Each Mail `Plugin` needs to implement `MailInterface` which has 2 methods: 
 - `format()` for preparation (concatenation, etc.)
 - `mail()` for the sending
 
To know which plugin to use the Mail Manager uses the configuration object called `system.mail`. We can modify this object using the `hook_install()` and `hook_uninstall()`.

## Implementing hook_mail()

Example: `22-hello_world-mail`
We will define our mail template using the hook_mail() in our hello_world.module file.

``` 
function hello_world_mail($key, &$message, $params) {
(...)
}
```

where:
 - $key is used to identify the type of message. In our case it is *hello_world_log*.
 - $message has the message that will be send and need to be filled in. In the case of $message['body'] notice that it is an array that will be imploded later with the *format()* method. In the case of $message['header'] it was already filled by the PhpMail plugin.
 - $param has parameters send from the client.


## Tokens