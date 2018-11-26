Drupal 8 Module Development Notes

# About hooks
 - By default we use hooks only in the `*.module` file
 - Use short and concise DocBlocks

# About Routes
https://www.drupal.org/docs/8/api/routing-system/structure-of-routes
 - `path` key indicates the path we want this route to work on
 - `defaults` section defines the handler
 - we can use *Route variables* like `path: '/hello/{param}'` and/or `/hello/{node}`

# Namespaces
 - Drupal 8 uses the PSR-4 namespace autoloading standard.
 - the base namespace is `\Drupal\module_name,`
 - we will need a /src folder inside our module to place all of our classes that need to be autoloaded.
 - the /src is the namespace root folder.

# Services
 - To make Controllers more minimalistic we use services.
 - A service is an object that gets instantiated by a Service Container and is used to handle operations in a reusable way,
 - Services are a core part of the dependency injection (DI) principle
 - they are globally registered with the service and instantiated only once per request (singleton).
 - It is a standard practice to have the service name start with your module name.
 - Once we clear the cache, the service will get instantiated.

```
services: 
  hello_world.salutation: 
    class: Drupal\hello_world\HelloWorldSalutation
```

# Tagged Services
 - Typically, these are picked up by a collector service

Example:

```
hello_world.salutation:
  class: Drupal\hello_world\HelloWorldSalutation
  tags:
    - {name: tag_name}
```
# Ways to use services in Drupal 8

There are two ways of using services in Drupal 8:

 1. statically by a static call to the Service Container:
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
  - Example: `\Drupal::entityTypeManager()`

Howto use a satic call:

```
$service = \Drupal::service('hello_world.salutation');
```
 
 2. injected using dependency injection to pass the object through the constructor (or in some rare cases, a setter method).
  - there are a few different ways to inject dependencies based on the receiver

# Injecting the service into a controller

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

# Forms: Admin Configuration Form

[API](https://api.drupal.org/api/drupal/elements/8.2.x)

 - We configure `*.routing.yml` to enable the link to the Form
 - Usually Form classes are in the `/Form` folder
 - We implement `FormInterface` and either can extend from:
   - `FormBase` or
   - `ConfigFormBase`
 - Four main methods that needs to be understand:
   - getFormId()
   - buildForm()
   - validateForm() with it's `$form_state` object (this method is not mandatory)
   - submitForm() receives the same arguments as `validateForm()`
 - Other methods that needs to be implemented:
   - getEditableConfigNames() that return configuration objets
 - forms can receive arguments from the Service Container in the same way we injected the salutation service into our Controller.
 - ConfigFormBase ,which we are extending in our preceding form above, injects the config.factory service because it needs to use it for reading and storing configuration values.

# Altering Forms

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
# Custom Submit Handlers

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

# Rendering Forms programmatically

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

# Service dependencies

 - We want to get now the salutation message from the admin configuration Form we createtd in the last step.
 - First we modify our service to accept an Drupal 8 configuration factory objet:
   `arguments: ['@config.factory']`
 - Now we can receive the argument in our service class:
    ```
    public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
    }
    ```

# Blocks
 - Custom blocks in Drupal 8 are **plugins**.
 - In Drupal 8, we work with a simple plugin class that can be made container-aware (that is, we can inject dependencies into it) and we can store configuration in a logical fashion.
 - Note: The *content* blocks that you create through the UI to place in a region and the custom blocks that are placed in a region are `content entities`.
 
## How do we create a custom block plugin easily?
 - We need one class, placed in the right namespace `Drupal\module_name\Plugin\Block`
 
Please have a look at our example in `/src/Plugin/Block`


 










