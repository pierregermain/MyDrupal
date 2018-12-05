# Drupal 8 Module Development Notes

## About hooks

 - By default we use hooks only in the `*.module` file
 - Use short and concise DocBlocks

## About Routes

 - [Documentation](https://www.drupal.org/docs/8/api/routing-system/structure-of-routes)
 - By default we define routes in the `*.routing.yml` file
 - `path` key indicates the path we want this route to work on
 - `defaults` section defines the handler
 - We can use *Route variables* like `path: '/hello/{param}'` and/or `/hello/{node}`

## Namespaces

 - Drupal 8 uses the PSR-4 namespace autoloading standard.
 - the base namespace is `\Drupal\module_name,`
 - We will need a /src folder inside our module to place all of our classes that need to be autoloaded.
 - The `/src` is the namespace root folder.

## Services

 - To make Controllers more minimalistic we use services.
 - A service is an object that gets instantiated by a Service Container and is used to handle operations in a reusable way.
 - Services are a core part of the *Dependency Injection* (DI) Principle.
 - Services are globally registered with the service and instantiated only once per request (*singleton*).
 - It is a standard practice to have the service name start with your module name.
 - Once we clear the cache, the service will get instantiated.

    ```
    services: 
      hello_world.salutation: 
        class: Drupal\hello_world\HelloWorldSalutation
    ```

### Tagged Services

 - Typically, these are picked up by a collector service

    Example:

    ```
    hello_world.salutation:
      class: Drupal\hello_world\HelloWorldSalutation
      tags:
        - {name: tag_name}
    ```

### Ways to use services in Drupal 8

There are two ways of using services in Drupal 8:

 **1. statically** by a static call to the Service Container:

  - We use this in:
    - `.module` files (but only put there code that should be there)
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
 
 **2. injected using dependency injection** to pass the object through the constructor (or in some rare cases, a setter method).

There are a few different ways to inject dependencies based on the receiver:

### Injecting the service into a controller

We put this code at the top to import the service class

```
use Drupal\hello_world\HelloWorldSalutation;
use Symfony\Component\DependencyInjection\ContainerInterface;
```

and after that we implement the code in our Controller class

```
public function __construct (...)
public static function create (...)
```

## Forms: Admin Configuration Form

 - Documentation [API](https://api.drupal.org/api/drupal/elements/8.2.x)
 - We configure `*.routing.yml` to enable the link to the Form
 - Usually Form classes will be in stored in the `/Form` folder
 - We implement [`FormInterface`](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Form%21FormInterface.php/interface/FormInterface/8.2.x) and either can extend from:
   - `[FormBase]`(https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Form%21FormBase.php/class/FormBase/8.2.x) or
   - `[ConfigFormBase](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Form%21ConfigFormBase.php/class/ConfigFormBase/8.2.x)`
 - Four main methods that needs to be understand:
   - getFormId()
   - buildForm()
   - validateForm() with it's `$form_state` object (this method is not mandatory)
   - submitForm() receives the same arguments as `validateForm()`
 - Other methods that needs to be implemented:
   - getEditableConfigNames() that return configuration objets
 - forms can receive arguments from the Service Container in the same way we injected the salutation service into our Controller.
 - ConfigFormBase ,which we are extending in our preceding form above, injects the config.factory service because it needs to use it for reading and storing configuration values.

## Altering Forms

Alterning form form other modules (this code gets executed for ALL forms):

```
/**
 * Implements hook_form_alter().
 */
function my_module_form_alter(&$form, \Drupal\Core\Form\FormStateInterface
    $form_state, $form_id) {
  if ($form_id == 'salutation_configuration_form') {
    // Perform alterations.
  }
}
```
Altering form from other modules (only for our form):

```
/**
 * Implements hook_form_FORM_ID_alter().
 */
function my_module_form_salutation_configuration_form_alter(&$form,
    \Drupal\Core\Form\FormStateInterface $form_state, $form_id) {
  // Perform alterations.
}
```
## Custom Submit Handlers

Typically, for the forms defined as we did, it's pretty simple. Once we alter the form and
inspect the $form array, we can find a #submit key, which is an array that has one item. 
This is simply the submitForm() method on the form class. So, what we
can do is either remove this item and add our own function or simply add another item to
that array

```(php)
/**
 * Implements hook_form_FORM_ID_alter().
 */
function my_module_form_salutation_configuration_form_alter(&$form,
    \Drupal\Core\Form\FormStateInterface $form_state, $form_id) {
  // Perform alterations.
  $form['#submit'][] = 'hello_world_salutation_configuration_form_submit';
}
```
And the callback we added to the #submit array above can look like this:

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

There is another case though. If the submit button on the form has a #submit property
specifying its own handler, the default form #submit handlers we saw just now won't fire
anymore. This was not the case with our form. So, in that situation, you will need to add
your own handler to that array. Hence, the only difference is the place you tack on the
submit handler. A prominent example of such a form is the Node add/edit form.

Finally, when it comes to the validation handler, it works exactly the same as with the
submit, but it all happens under the #validate array key.

## Rendering Forms programmatically

 - We can do this using the `FormBuilder` service
 - We get the form builder and request from it the form using the fully qualified name of the form class.
 - The form builder can be injected using the `form_builder` service key or used statically via
the shorthand:

    ```
    $builder = \Drupal::formBuilder();
    ```
   - Once we have it, we can build a form, like so:
   
    ```
    $form = $builder->getForm('Drupal\hello_world\Form\SalutationConfigurationForm');
    ```
    
In the preceding code, `$form` will be a render array of the form that we can return, for
example, inside a Controller.

## Service dependencies

 - We want to get now the salutation message from the admin configuration Form we createtd in the last step.
 - First we modify our service to accept an Drupal 8 configuration factory objet:
   `arguments: ['@config.factory']`
 - Now we can receive the argument in our service class:
    ```
    public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
    }
    ```

## Blocks
 - Custom blocks in Drupal 8 are **plugins**.
 - In Drupal 8, we work with a simple plugin class that can be made container-aware (that is, we can inject dependencies into it) and we can store configuration in a logical fashion.
 - Note: The *content* blocks that you create through the UI to place in a region and the custom blocks that are placed in a region are `content entities`.
 
### How do we create a custom block plugin easily?
 - We need one class, placed in the right namespace `Drupal\module_name\Plugin\Block`
 - We need to use annotations: `id` and `admin_label`
 - Note that each kind of plugin needs some kind of annotations.
 
Please have a look at our example in `/src/Plugin/Block`

```
(... imports and annotations ...)

class HelloWorldSalutationBlock 
  extends BlockBase // provides a number of helpful things a block plugin needs
  implements ContainerFactoryPluginInterface // to make things easier: this gives us the construct() and create() functions. 
...
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

let's imagine that we need a Boolean-like control on our block configuration so that when an admin places the block, they can toggle something and that value can be used in the build() method. We can achieve this with three to four methods on our plugin class.

We add:

defaultConfiguration() 
blockForm()
blockSubmit(
 

after that we ca use in build()

$config = $this-> getConfiguration();


also keep in mind that we can use blockValidate and blockSubmit


## Links

There are two main aspects when talking about link building in Drupal the *URL* and the actual *link tag* itself. So, creating a link involves a two-step process that deals with these two, but can also be shortened into a single call via some helper methods.


### The Url


 - represented by the `Drupal\Core\Url` Class

 - Static methods:
   `::fromRoute() to create new instance of `Url`
   `::fromUri()  have a look at the documentation above the code.

 - use the $option array to configure your instance.
 - Always work with route names, not with hardcoded urls.

### The Link

Once we have a `Url` object we can create the link.

2 ways to create links:

 1. Use `LinkGenerator` aka `link_generator` service using the `generate()` method. This will return a `GeneratedLink` object with the string needed.

```
$url = Url::fromRoute('my_route', ['param_name' => $param_value]); 
$link = \Drupal::service('link_generator')->generate('My link', $url);
```

We can then directly print $link because it implements the `__toString()` method.

 2. Use `Link` class which wraps a render element (used in for theming, good if you need to share this data without services)

```
$url = Url::fromRoute('my_other_route'); 
$link = Link::fromTextAndUrl('My link', $url);
```

We now have $link as a `Link` object whose `toRenderable()` returns a render array of the `#type => 'link'`. Behind the scenes, at render time, it will also use the link generator to transform that into a link string.

If we have a Link object, we can also use the link generator ourselves to generate a link based on it:
`$link = \Drupal::service('link_generator')->generateFromLink($linkObject);`

## Event Dispatcher and redirects

In D7 dynamic redirect could be done using the `hook_init()` which gets called on each request and then use the `drupal_goto()` function. 

In D8 we would subscribe to `kernel.request` event.

### Redirecting from a Controller

In our controller instead of returning our render array we could return `return new RedirectResponse('node/1');` using the Symfony HTTP Foundation component.


### Redirecting from a subscriber

#### Event Dispatcher

registering event subscribers is a matter of creating a service tagged with `event_subscriber` and that implements the interface.

Example:

Let's now take a look at an example event subscriber that listens to the kernel.request
event and redirects it to the home page if a user with a certain role tries to access our Hello
World page. This will demonstrate both how to subscribe to events and how to perform a
redirect. It will also show us how to use the current route match service to inspect the
current route.


Let's create this subscriber by first writing the service definition for it

```
hello_world.redirect_subscriber:
  class: \Drupal\hello_world\EventSubscriber\HelloWorldRedirectSubscriber
  arguments: ['@current_user']
  tags:
    - {name: event_subscriber}
```

The dependency is actually the service that points to the current user (`AccountProxyInterface` object)

Now have a look at /src/EventSubscriber.php

We store the info of the logged in user in $currentUser


the important code is

```
public function onRequest( GetResponseEvent $ event) { $ route_name = $ this-> currentRouteMatch-> getRouteName(); if ($ route_name != = 'hello_world.hello') { return; } $ roles = $ this-> currentUser-> getRoles(); if (in_array(' non_grata', $ roles)) { $ url = Url:: fromUri(' internal:/'); $ event-> setResponse( new RedirectResponse( $ url->toString())); } }
```

From the `CurrentRouteMatch` service, we can figure out the name of the current route, the entire route object, parameters from the URL, and other useful things.


the url is build with the Url class


## Dispatch your own events

we have seen howto sibscribe. now lets see howto dispatch events

this way we can tell other modules that some function in our module has been executed.

have a look at /src/SalutationEvent.php
that extends Event. it has the $message with setters and getters.














