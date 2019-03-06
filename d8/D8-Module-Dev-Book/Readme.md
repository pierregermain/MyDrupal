# Drupal 8 Module Development Notes

- [About hooks](#about-hooks)
- [About Routes](#about-routes)
- [Namespaces](#namespaces)
- [Services](#services)
	- [Tagged Services](#tagged-services)
	- [Ways to use services in Drupal 8](#ways-to-use-services-in-drupal-8)
	- [Injecting the service into a controller](#injecting-the-service-into-a-controller)
- [Forms: Admin Configuration Form](#forms-admin-configuration-form)
- [Altering Forms from other modules](#altering-forms-from-other-modules)
- [Custom Submit Handlers](#custom-submit-handlers)
	- [Typical handler](#typical-handler)
	- [Special handler](#special-handler)
- [Custom Validation Handlers](#custom-validation-handlers)
- [Rendering Forms programmatically](#rendering-forms-programmatically)
- [Service dependencies](#service-dependencies)
- [Blocks](#blocks)
	- [How do we create a custom block plugin easily?](#how-do-we-create-a-custom-block-plugin-easily)
- [Block Configuration](#block-configuration)
- [Links](#links)
	- [The Url](#the-url)
	- [The Link](#the-link)
- [Event Dispatcher and redirects](#event-dispatcher-and-redirects)
	- [Redirecting from a Controller](#redirecting-from-a-controller)
	- [Redirecting from a subscriber](#redirecting-from-a-subscriber)
		- [Event Dispatcher](#event-dispatcher)
- [Dispatch your own events](#dispatch-your-own-events)

[TOC generator](https://magnetikonline.github.io/markdown-toc-generate/)

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

 - We have seen howto subscribe to events. 
 - Now lets see howto dispatch events.
 - This way we can tell other modules that some function in our module has been executed.

Example:

Have a look at `/src/SalutationEvent.php` that extends `Event`. It has the `$message` string with setter and getter methods.














