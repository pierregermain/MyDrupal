# Drupal 8 Module Development Notes

[TOC GENERATE](https://magnetikonline.github.io/markdown-toc-generate/)

- [T1 Introduction](#t1-introduction)
- [T2 Your first module](#t2-your-first-module)
  - [Creating hooks](#creating-hooks)
  - [Creating Routes and Controllers](#creating-routes-and-controllers)
    - [Route Variables](#route-variables)
      - [Parameter Converters](#parameter-converters)
  - [About Namespaces](#about-namespaces)
  - [Creating a Controller](#creating-a-controller)
  - [Creating a Service](#creating-a-service)
    - [Tagged Services](#tagged-services)
    - [Ways to use services in Drupal](#ways-to-use-services-in-drupal)
    - [Injecting the service into our controller](#injecting-the-service-into-our-controller)
  - [About Forms](#about-forms)
    - [Forms: Admin Configuration Form](#forms-admin-configuration-form)
    - [Altering Forms from other modules](#altering-forms-from-other-modules)
    - [Custom Submit Handlers](#custom-submit-handlers)
      - [Typical handler](#typical-handler)
      - [Special handler](#special-handler)
      - [Custom Validation Handlers](#custom-validation-handlers)
    - [Rendering Forms (programmatically)](#rendering-forms-programmatically)
  - [Service dependencies](#service-dependencies)
  - [Blocks](#blocks)
    - [How do we create a custom block plugin easily?](#how-do-we-create-a-custom-block-plugin-easily)
  - [Block Configuration](#block-configuration)
  - [Working with Links](#working-with-links)
    - [1. The Url](#1-the-url)
    - [2. The Link](#2-the-link)
  - [Event Dispatcher and redirects](#event-dispatcher-and-redirects)
    - [Redirecting from a Controller](#redirecting-from-a-controller)
    - [Event Dispatcher](#event-dispatcher)
      - [Redirecting from a subscriber](#redirecting-from-a-subscriber)
  - [Dispatch your own events](#dispatch-your-own-events)
  - [What can Subscribers do](#what-can-subscribers-do)

- [T3 Logging and Mailing](#t3-logging-and-mailing)
  - [Introduction](#introduction)
  - [Logging](#logging)
    - [The Drupal 8 Logging theory](#the-drupal-8-logging-theory)
    - [Our own logger channel](#our-own-logger-channel)
      - [Our own logger](#our-own-logger)
      - [Our own logger](#our-own-logger-1)
        - [Logging in Hello World](#logging-in-hello-world)
  - [Mailing](#mailing)
    - [Introduction](#introduction-1)
    - [Implementing hook_mail()](#implementing-hook_mail)
      - [Sending Mails](#sending-mails)
    - [Alter Mails](#alter-mails)
    - [Custom mail plugins](#custom-mail-plugins)
      - [The mail plugin](#the-mail-plugin)
      - [Using our own plugin](#using-our-own-plugin)
  - [Tokens](#tokens)
    - [The Token API](#the-token-api)
      - [Using Token API](#using-token-api)
      - [Defining new Tokens](#defining-new-tokens)

# T1 Introduction

- Drupal is a CMS and a CMF (Content Management Framework)
- Drupal Techs:
  - PHP
  - DB: PHP Data Objects
  - Web server
  - HTML, CSS, JS
 
- Drupal Architecture:
  - Core
  - Modules
  - Themes
  - Hooks (D7) and
   - Plugins (D8)
   - Events (EventDispatcher of Symfony)
  - Services and
   - Service Dependency Injection container
   
- From Request to Reponse:
  - we enter in /node/123
  - web server recognizes that we need PHP
  - PHP executes index.php of Drupal (it's the front controller) and creates an `Request` object
  - Symfony HTTPKernel creates events such as `kernel.request`, etc.
  - The route is defined from the `kernel.request` event
  - The route controller is identified and the `kernel.controller` event is used.
  - After some processing usually an *render array* is returned and transformed into an `Response` Object.
  
As a Drupal Dev you will spend your time inside controllers and services.

- Drupal's mayor subsystems
 - Routing: Path translates to a route using the *Symfony Routing component*.
 - Entities. Types:
  - Content (Node, User, Comment) with bundles (Articles, Basic Page, etc.)
  - Configuration with bundles
 - Fields: Stored on entities. Types:
  - base fields (code)
  - configuration fields (UI)
 - Menus with its API
 - Views
 - Forms using its API
 - Configuration using YML files
 - Plugins (For example Blocks) using Annotations to be discovered
 - Theme System
 - Caching
 
- Tools for developers:
 - composer
 - API site and coding standards
 - devel module
 - drush and drupal console
 - developer settings 
 
# T2 Your first module

## Creating hooks

Example: `01-hello_world-hook`

This examples creates an help page at
http://my-drupal.loc/admin/help/hello_world

 - By default we use hooks only in the `<module>.module` file
 - Use short and concise DocBlocks

## Creating Routes and Controllers

Example: `02-hello_world-routes`

In `hello_world.routing.yml` we define our route. But if you do not define a controller related to the route it will give you a Page Not Found error. 

Important Notes:

 - [Documentation](https://www.drupal.org/docs/8/api/routing-system/structure-of-routes)
 - By default we define routes in the `<module>.routing.yml` file
 - `path` key indicates the path we want this route to work on
 - `defaults` section defines the handler
 
### Route Variables

We can use **Route variables** like `path: '/hello/{param}'` or `/hello/{node}`:
   - The controller gets as an argument the `$param` or `$node` variable.
   
#### Parameter Converters

The parameters can be autoloaded with a converter using:

``` 
options:
  parameters:
    param:
      type: entity: node
```

In this case we would get converted the node ID to $node entity. That is *sehr Praktisch*.

Keep in mind that if you name your parameter *{node}* drupal will know that it has to convert it automatically to a $node entity (because the machine name is `node`).

## About Namespaces

 - Drupal 8 uses the PSR-4 namespace autoloading standard.
 - All namespaces for Drupal core and modules classes start with `\Drupal`.
 - The base namespace for a module is `\Drupal\module_name,`
 - We will need a /src folder inside our module to place all of our classes that need to be autoloaded.
 - The `/src` is the namespace root folder.

## Creating a Controller

Example: `03-hello_world-controller`

We create the file /src/Controller/HelloWorldController.php that extends the `ConrollerBase`
and a route to /hello-world named hello_world.hello_world

We should do this with the Drupal Console to not commit syntax errors :). It will generate for us the boiler plate for the route and the controller.

## Creating a Service

Example: `04-hello_world-service`

Information:
 - To make Controllers more minimalistic we use services.
 - A service is an object that gets instantiated by a Service Container and is used to handle operations in a reusable way.
 - Services are a core part of the *Dependency Injection* (DI) Principle.
 - Services are globally registered with the service and instantiated only once per request (*singleton*). So you should write your services so that it stays immutable.
 
Sample code:
 - `/core/core.services.yml` for core services.
 - `*.services.yml` in modules.

Our Example:
 - We create a Service Class in `/src/HelloWorldSalutation.php` (thanks to PSR-4 it get autoloaded).
 - We register it with the Service Container and use it from there as a DI:
   - We create the `hello_world.services.yml` file that begins with the `services` key.
   - Our service is called `hello_world.salutation`

```
services:
  hello_world.salutation:
    class: Drupal\hello_world\HelloWorldSalutation
    arguments: []
```

Once we clear the cache, the service will be available to be used.
 
### Tagged Services

 - We use tags to inform the container as to a specific purpose that they serve.
 - We can also define priorities 

Example:

```
hello_world.salutation:
  class: Drupal\hello_world\HelloWorldSalutation
  tags:
    - {name: tag_name}
```



### Ways to use services in Drupal

Once you have created a service there are 2 ways of using services in Drupal 8:

 **1. statically** by a static call to the Service Container:

  - We use this in:
    - `.module` files (Remember to only put there code that should be there)
    - hooks
    - Drupal Procedural Code
  - Some important services exist in the `\Drupal` class
  - We should not use static calls when we can use dependency injection:
    - Controller
    - Service
    - Plugin
    - Other classes that accept dependency injection
  - Example 1: [`\Drupal::entityTypeManager()`](https://api.drupal.org/api/drupal/core!lib!Drupal.php/function/Drupal%3A%3AentityTypeManager/8.2.x)

  - Example 2: Howto use a static call:

```
    $service = \Drupal::service('hello_world.salutation');
```
 
 **2. injected** using dependency injection to pass the object through the constructor (or in some rare cases, a setter method).

There are a few different ways to inject dependencies based on the receiver, for now we will just see how to inject the service into a controller.

### Injecting the service into our controller

We import the following in our `/src/Controller/HelloWorldController` class.

```
use Drupal\hello_world\HelloWorldSalutation;
use Symfony\Component\DependencyInjection\ContainerInterface;
```

and after that we implement the following code:

```
protected $salutation;
public function __construct (takes our service as an argumen and stores it in the $salutation property)
public static function create (recives the Service Container as a parameter)
```

Because our class implements `ContainerInjectionInterface` thanks to extending `ControllerBase` the first method that will get called is the `create` method that tells with service should be injected.

## About Forms

API: https://api.drupal.org/api/drupal/elements/

### Forms: Admin Configuration Form

Example: `05-hello_world-form`
 - We want to be able to change the greeting.
 - We create a route in hello_world.routing.yml file that points to our new config Page
   - path: `/admin/config/salutation-configuration`
   
 - We create our Form at /src/Form/SalutationConfigurationForm.php. We use **configuration factory** to store the value (defined in the *ConfigFormBaseTrait*).

Important Info:

 - Official documentation [API](https://api.drupal.org/api/drupal/elements/8.2.x) link.
 - We configure `*.routing.yml` to enable the link to the Form
 - Usually Form classes will be stored in the `/src/Form` folder
 - We implement [`FormInterface`](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Form%21FormInterface.php/interface/FormInterface/8.2.x) and either can extend from:
   - [`FormBase`](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Form%21FormBase.php/class/FormBase/8.2.x) or
   - [`ConfigFormBase`](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Form%21ConfigFormBase.php/class/ConfigFormBase/8.2.x): for system configuration forms.
 - Four main methods that needs to be understood:
   - [getFormId()](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Form%21FormInterface.php/function/FormInterface%3A%3AgetFormId/8.2.x): returns a unique machine-name for the form.
   - [buildForm()](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Form%21FormInterface.php/function/FormInterface%3A%3AbuildForm/8.2.x): returns the form definition and metadata.
   - [validateForm()](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Form%21FormInterface.php/function/FormInterface%3A%3AvalidateForm/8.2.x) that gets the form definition and an `$form_state` object that has the submitted values (this method is NOT mandatory)
   - [submitForm()](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Form%21FormInterface.php/function/FormInterface%3A%3AsubmitForm/8.2.x) receives the same arguments as `validateForm()`.
 - Other methods that needs to be implemented:
   - `getEditableConfigNames()` from the [`ConfigFormBaseTrait`](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Form%21ConfigFormBaseTrait.php/trait/ConfigFormBaseTrait/8.2.x) used in the `ConfigFormBase`. This method returns configuration objects so that we can edit configurations.
 - Forms can receive arguments from the Service Container in the same way we injected the salutation service into our Controller.
 - `ConfigFormBase`, which we are extending injects the `config.factory` service because it needs to use it for reading and storing configuration values.


### Altering Forms from other modules

Remember that Drupal has *alter* hooks to allow other modules to make changes to an array or an object before the array or object is used.

- Option 1: using `form_alter` hook.
   - Disadvantage: code gets executed for **ALL** forms.

```
/**
 * Implements hook_form_alter().
 */
function my_module_form_alter(&$form, 
  \Drupal\Core\Form\FormStateInterface $form_state, $form_id) {
  if ($form_id == 'salutation_configuration_form') {
    // Perform alterations.
  }
}
```

- Option 2: using `form_FORM_ID_alter` hook.
   - Advantage: code gets executed only for our form:

```
/**
 * Implements hook_form_FORM_ID_alter().
 */
function my_module_form_salutation_configuration_form_alter(&$form,
    \Drupal\Core\Form\FormStateInterface $form_state, $form_id) {
  // Perform alterations.
}
```

### Custom Submit Handlers

#### Typical handler

Usually once we alter the form and inspect the `$form` array, we can find a `#submit` key, which is an array that has one item. 
This is simply the `submitForm()` method on the form class. 
So we can either remove this item and add our own function or simply add another item to that array.

Example: Adding our own function:

```(php)
/**
 * Implements hook_form_FORM_ID_alter().
 */
function my_module_form_salutation_configuration_form_alter(&$form,
    \Drupal\Core\Form\FormStateInterface $form_state, $form_id) {
  // Perform alterations.
  $form['#submit'][] = 'my_module_salutation_configuration_form_submit';
}
```

And the callback we added to the `#submit` array above can look like this:

```(php)
/**
 * Custom submit handler for the form_salutation_configuration form.
 *
 * @param $form
 * @param \Drupal\Core\Form\FormStateInterface $form_state
 */
function my_module_salutation_configuration_form_submit(&$form,
    \Drupal\Core\Form\FormStateInterface $form_state) {
  // Do something when the form is submitted.
}
```

#### Special handler

 - There is another case though. 
 - If the submit button on the form has a `#submit` property specifying its own handler, the default form #submit handlers we saw just now won't fire anymore. 
 - This was not the case with our form. 
 - In that situation, you will need to add your own handler to that array (the button array). Hence, the only difference is the place you tack on the submit handler. A prominent example of such a form is the *Node add/edit form*.

#### Custom Validation Handlers

Finally, when it comes to the validation handler, it works exactly the same as with the
submit, but it all happens under the `#validate` array key.

### Rendering Forms (programmatically)

Sometimes we need to render a form from a Controller or Block.

 - We can do this using the `FormBuilder` service
 - We get the form builder and request from it the form using the fully qualified name of the form class.
 - The form builder can be injected using the `form_builder` service key or used statically via
the shorthand:

```
$builder = \Drupal::formBuilder();
```

Once we have it, we can build a form, like so:
   
```
$form = $builder->getForm('Drupal\hello_world\Form\SalutationConfigurationForm');
```
    
In the preceding code, `$form` will be a render array of the form that we can return, for
example, inside a Controller.

## Service dependencies

Example: `06-hello_world-service-dependency`

 - We want to get now the salutation message from the admin configuration Form we createtd in the last step.
 - First we modify our service to accept an Drupal 8 configuration factory object (That's the service responsible for loading config objects):
   `arguments: ['@config.factory']`
    - Note that `@config.factory` is defined in `core.services.yml` to the  `Drupal\Core\Config\CoreFactory` class.
 - Now we receive the `$config_factory` argument in our service class:

```

use \Drupal\Core\Config\ConfigFactoryInterface;

(...)

public function __construct(ConfigFactoryInterface $config_factory) {
  $this->configFactory = $config_factory;
}

(...)

public function getSalutation() {
  $config = $this->configFactory->get('hello_world.custom_salutation');
  $salutation = $config->get('salutation');
  
  (...)
  
}

```

## Blocks

 - Custom blocks in Drupal 8 are **plugins**.
 - In Drupal 8, we work with a simple plugin class that can be made container-aware (that is, we can inject dependencies into it) and we can store configuration in a logical fashion.
 - Note: The *content* blocks that you create through the UI to place in a region and the custom blocks that are placed in a region are `content entities`.
 
### How do we create a custom block plugin easily?

Example: `07-hello_world-block` in the `/src/Plugin/Block` folder.
 - We create a simple block that will render hello world as our previous controller did.  
 - From the UI you will be able to add the block called *Hello world salutation*.

Keep in mind:
 - We need one class, placed in the right namespace `Drupal\module_name\Plugin\Block` (PSR-4 auto-loading)
 - We need the following annotation (*id* and *admin_label*):
 
 ```
 * @Block(
 *  id = "hello_world_salutation_block",
 *  admin_label = @Translation("Hello world salutation"),
 * )
 ```
 
 - Note that each kind of plugin needs some kind of annotations (Have a look at `AnnotationInterface` to see which annotations you need).

```
(...) 
imports
annotations
(...)

class HelloWorldSalutationBlock 
  extends BlockBase // provides a number of helpful things a block plugin needs
  implements ContainerFactoryPluginInterface // make things easier: gives construct() and create() functions. 
  
  (...)
  
__construct () // makes container aware

create() with the following args:

  ContainerInterface $container //
  array $configuration // configuration values that were stored with the plugin (or passed when building)
  $plugin_id // ID set in the plugin annotation (or other discovery mechanism
  $plugin_definition // metadata on this plugin (including all the info found in the annotation)
 
build() // responsible for building the block content.

(...)

```

## Block Configuration

Example: `08-hello_world-block-configuration`
File: `/src/Plugin/Block/HelloWorldSalutationBlock.php`

Let's imagine that we need a Boolean-like control on our block configuration so that when an admin places the block, they can toggle something and that value can be used in the build() method. 
 - We can achieve this with three to four methods on our plugin class. 
   - remember that our block is in `/src/Plugin/Block`.

We need to implement:

```
defaultConfiguration() // defines the configuration items with there default values for this block
blockForm() // gives definitions to each item
blockSubmit()
``` 

after that we ca use in the build() function

```
$config = $this-> getConfiguration();
```

also keep in mind that we can use `blockValidate` and `blockSubmit`.


## Working with Links

Example: `08-hello_world-link`
We just print the actual link in the block (/src/Plugin/Block) using the 2 ways presented here.
We show those links if the Checkbox in Enabled.

There are two main aspects when talking about link building in Drupal:   
 - the *URL* and
 - the actual *link* tag itself.   

So, creating a link involves a two-step process that deals with these two, 
but can also be shortened into a single call via some helper methods.


### 1. The Url

 - represented by the [`Drupal\Core\Url`](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Url.php/class/Url/8.2.x) Class

 - Static methods:
   - [`Url::fromRoute()`](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Url.php/function/Url%3A%3AfromRoute/8.2.x) 
   to create new instance of `Url` from a Drupal routes.
   - [`Url::fromUri()`](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Url.php/function/Url%3A%3AfromUri/8.2.x) 
   to create a new instance of `Url` from an internal or external URL.

 - Keep in mind that passing the `$option` array you can configure your instance. [Examples at ::fromUri()](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Url.php/function/Url%3A%3AfromUri/8.2.x).
 - Try to work with route names, not with hardcoded urls.

### 2. The Link

Once we have a `Url` object we can generate the link.

There are two ways to create links:

 1. Use `LinkGenerator` service (named `link_generator`) using the `generate()` method. This will return a `GeneratedLink` object with the string needed.

    ```
    $url = Url::fromRoute('my_route', ['param_name' => $param_value]); 
    $link = \Drupal::service('link_generator')->generate('Link anchor name', $url);
    ```
    
    We can then directly print `$link` because it implements the `__toString()` method.

 2. Use `Link` class which wraps a render element (used in for theming, good if you need to share this data without services)

    ```
    $url = Url::fromRoute('my_route'); 
    $link = Link::fromTextAndUrl('Link anchor Name', $url);
    $link = $link->toString();
    ```

We now have $link as a `Link` object whose `toRenderable()` returns a render array of the `#type => 'link'`. 
Behind the scenes, at render time, it will also use the link generator to transform that into a link string.  

If we have a Link object, we can also use the link generator ourselves to generate a link based on it:
`$link = \Drupal::service('link_generator')->generateFromLink($linkObject);`

## Event Dispatcher and redirects

In D7 dynamic redirect could be done using the `hook_init()` which gets called on each request and then use the `drupal_goto()` function. 

In D8 we would subscribe to the `kernel.request` event and change the response directly.

### Redirecting from a Controller

Example: `09-hello_world-redirect-from-controller`

In our controller instead of returning our render array we could return `new RedirectResponse('node/1');` using the *Symfony HTTP Foundation* component.

We add the following to examples: In the first example we response with an response object directly.
```
  public function helloWorld() {
    return new Response ('my text');
  }
```

In the second we response with an Response redirect object.
```
  public function helloWorld() {
    return new RedirectResponse('node/1');
  }
```


### Event Dispatcher

The Event Dispatcher allows us to dispatch and subscribe to events.

#### Redirecting from a subscriber

Example: `10-hello_world-subscriber`
Link: /redirect

Usually we want to redirect from a certain page to another if various conditions match. For this we can subscribe to the request event and change the response.

System that makes this happen is the `event_dispatcher` service:
 - it is an instance of the `ContainerAwareEventDispatcher`.
 - it dispatches *named* events (as Event objects)
 
Instances of `EventSubsciberInterface`:
 - listen to named event.
 - allow subscribers to change the data before the logic comes in
 
So, Registering event subscribers is a matter of creating a service tagged with `event_subscriber` and that implements the interface.

Example:

 - Event subscriber listens to the *kernel.request* event 
 - Event subscriber redirects it to the home page if a user with a certain role tries to access our Hello World page. 
 - This will demonstrate:
     - how to subscribe to events and how to perform a redirect. 
     - how to use the current route match service to inspect the current route.  

We add the following to our service file 
 - The dependency is actually the service that points to the current user (`AccountInterface` object).
 - In our example we check if $current_user has the non grata role (anonymous user).
 - We also add the current_route_match service to our service file.

```
hello_world.redirect_subscriber:
  class: \Drupal\hello_world\EventSubscriber\HelloWorldRedirectSubscriber
  arguments: ['@current_user', '@current_route_match']
  tags:
    - {name: event_subscriber}
```


We add the following Subscriber Class: `/src/EventSubscriber/HelloWorldRedirectSubscriber.php`

We store the info of the logged in user in `protected $currentUser` and the current route in `protected $currentRouteMatch`

The important code is

```
public function onRequest(GetResponseEvent $event) { 
  $route_name = $this->currentRouteMatch->getRouteName(); 
  (...)
  $roles = $this->currentUser->getRoles(); 
  (...)
  $event->setResponse( new RedirectResponse($url->toString())); 
}
```

From the `CurrentRouteMatch` service, we can figure out:
 - the name of the current route, 
 - the entire route object, 
 - parameters from the Url and 
 - other useful things.


## Dispatch your own events

 - We have seen how to subscribe to events. 
 - Now lets see how to dispatch events.
 - This way we can tell other modules that some function in our module has been executed.

Example: `11-hello_world-dispatch-events`
File: `/src/SalutationEvent.php` is our `Event` class
Link: http://my-drupal.loc/hello-world

We will create an event to be dispatched when `HelloWorldSalutation::getSalutation()` method is called. Other modules will be able to alter this message.

Steps:
  1. First we create the SalutationEvent class that extends Event. It has the $message property with setters and getters.
  2. Second we inject the *Event Dispatcher service* into our `HelloWorldSalutation` service. We just need to add an `argument` called `@event_dispatcher` into the services.yml file.
  3. Third we receive the new argument in the constructor (Drupal\hello_world\HelloWorldSalutation)
    - We also need to dispatch the event using `$event = this->eventDispatcher->dispatch(SalutationEvent::EVENT,$event`.
    - After that we get the value with `$event->getValue();`. We do that so that other modules can change the value.

## What can Subscribers do

After we have created our own event we can subcribe to it listening to `SalutationEvent::EVENT`. We can also use `stopPropagation()` to no longer trigger other listeners.

# T3 Logging and Mailing

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

### Sending Mails

The sending is done in the `log()` method in our `MailLogger` class.

``` 
\Drupal::service('plugin.manager.mail')->mail('hello_world', 'hello_world_log', $to, $langode, ['message' => $markup, 'user' => $account]);
```

where:
 - hello_world is our module
 - hello_world_log is our template

We also add new arguments to our constructor (so we also need to add them to the service definition in the services file)

## Alter Mails

We could alter mails using the `hook_mail_alter` that will modify the $message array created with the hook_mail() in the previous section.
This will be called every time a mail is send so be careful using this.

``` 
/**
* Implements hook_mail_alter().
*/
function hello_world_mail_alter(&$message) {
  switch ($message['key']) {
    case 'hello_world_log':
        $message['headers']['Content-Type'] = 'text/html; charset=UTF-8; format=flowed; delsp=yes';
        break;
  }
}
```

## Custom mail plugins

The default PHP mailer might not be enough for our application, we might want to use an external API.

### The mail plugin

Example: `23-hello_world-mail-plugin` 
file: `/src/Plugin/Mail/HelloWorldMail.php`

Parts of the class:
 - nice Plugin annotation
 - implements MailInterface
 - format method (exact copy of default PhpMail plugin)
 - create method (There we would inject/import our external PHP API library)
 - mail method (there we will add our own implementation to our API)

### Using our own plugin

file: `hello_world.install`
There is no UI to select which plugin the mail manager should use. 

Usually we would implement the hook_install and hook_uninstall to be able to change the default manager

We have 3 options (Will use the third one)
 1. All emails send with our plugin
 2. All emails sent for a module with a specific key (i.e. template) will use our plugin
 3. All emails send from our module will use our plugin

You will have to uninstall / install to test this functionality. By default I have commented out the install script.

``` 
drupal module:uninstall hello_world
drupal module:install hello_world
```

# Tokens

Example: `24-hello_world-token`

The great thing about tokens is the UI component. There are modules to define strings and fill them up with tokens.

We want to include some personalized information in the mail text without hardcoding it.

## The Token API

About:
 - Tokens in D8 are a standard formatted placeholders, which can be found inside a string and replaced by a real value from an related object.
 - Tokens are flexible: you can define groups which contain related tokens.
 - You will find many existing tokens that you can use in your code
 - You can define your own tokens

Format:
``` 
type:token
```
where:
 - `type` is the machine-readable name of a token type (a group of tokens)
 - `token` is the machine-readable name of a token within a group
 
Components:
 1. `hook_token_info()` (to define token types and tokens)
 2. `hook_tokens()` (fired when a token is found in a string)
 3. `Token` service (to do the replacement)
 
 Documentation:
  - [drupal.org](https://www.drupal.org/project/api_tokens)
  - [token.api.php file](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Utility%21token.api.php/8.2.x)

### Using Token API

Our custom `src/Logger/MailLogger` has a `$context` array in its `log()` method. That array has info about what will be logged.

We will now get our user account from within our `MailLogger::log()` method (using `$account = $context['user'];` )

This $account variable will have useful information about our logged in user (but keep in mind that is has not all the information about the user)

And finally we will alter our `hook_mail()` in our `*.module` file to replace a token:

```
if (isset($params['user'])) {
   $user_message = 'The user that was logged in: [current-user:name]';
  $message['body'][] = \Drupal::token()->replace($user_message, ['current-user' => $params['user']]);
}
```

Now when we get `user` parameter we will add a new message to the email informing who was logged in. 
We use the `token` service to replace the string. The `replace` method takes the $user_message string 
and as a paramter the token to be searched (in this case the 'current-user' string of the type 'user').


### Defining new Tokens

We want a dynamic "Hello World" message. We will expose the message with a token!

First we implement the `hook_token_info()` in our module file. There we define a type and a token and return an array containing both.
After that we implement the `hook_tokens()` to handle the replacement of our token.

**TODO**: Explain this process (p.92) or watch https://drupalize.me/videos/what-are-tokens?p=1701

To use the token we can use:

``` 
$final_string = \Drupal::token()->replace('The salutation text is:[hello_world:salutation]');
```

**TODO** Insert the code to make it work

