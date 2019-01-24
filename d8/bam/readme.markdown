# BAM - Drupal 8 Developer Preparation

# T1 Intro

We will cover:
 - Build a simple module
 - OOP
 - Namespacing
 - Autoloading
 - Symfony integration
 - Controllers
 - Composer
 - Custom Code
 - Migrate Modules
 - Unit and functional testing
 - Plugins
 
# T2 Build a dummy module

Example: `01-glue`

To enable a module you need two files
 - *.info.yml file
 - *.module file


## Add a Form Alter Hook

Example: `02-glue`. This example adds a alter hook to the *.module file.

Usually Hooks will be added to the module file.  

## Add a Controller to add a page manually

Example: `03-glue-controller`

 - Drupal uses PSR-4 so we need: 
   - `/src/Controller/mycontroller.php`: It will have a method that prints the markup. We could also return other type of data like JSON, XML, etc.
   - `routing.yml`: It defines our link and the method that will handle that url.

We can add a Controller using the *Drupal Console*. It will add the following files
```
 modules/glue/src/Controller/MyController.php
 modules/glue/glue.routing.yml
```

Now, if you go to `glue/hello/hola` it will print `hola`.

# T3 : OOP

## Procedural Example 

Example: `04-procedural-form`

We begin to implement a form that is procedural (no oop).
If we look at it, we see that our page and form could be objects.


## Oop Examples

Example: `05-oop-simple-form`

Tips: 
 - Be Agile (adaptable code) but
   - Only create new Object Types when you need them!
   - DRY: Do not repeat yourself!

### Extending a Class

Example: `06-oop-extending-a-class`
Test Url: http://my-drupal.loc/example/?print=1

We add the class `PrintedPage` that extends the Page class so that it inheritances all its functions and properties.

Now inside the *ContactUsController* we check if the `print` variable is set.


### Creating an Interface

Example: `07-oop-interface`

We add the interface named Page with its
    function build();
    function theme();

and now our *DefaultPage* and *PrintedPage* classes implement the Page interface requiring them to define those functions.

### Creating an Abstract Class

Example: `08-oop-abstract`

We define an abstract class named Page. It also has defined an abstract function named theme (that must be implented if we extend the Page class)
Being abstract means that the class Page can't be used if not being extended.

Tips:
 - Caution with using abstract classes: It really can consume more horse power.
 - Build your app as if it was an public api.
 
## Public vs. Private vs. Protected Visibility

Example: `09-oop-public-api`

Type of visibility:

 - PUBLIC: The Form class has public properties, so they can be used inside of the Form class or classes that extend the class.
 - PROTECTED: The properties can't be accessed from instances of the class.
 - PRIVATE: The properties can't be accessed from instances nor from classes that extend the class.
   As an example we have the validate and submit functions inside the Form class set to private because we do not need to call that class from outside of the class.

Tips: 
 - Each Class should go to its own File!
 - Each class File will be named as its containing class. If not possible you will need to use *namespacing*.
 - Make your properties not to public.
 - In the future you can always make them visible if needed (it's easier like that)

Note:
 - `__DIR__` points to the current directory.


# T4: Namespacing and Autoloading

## Conflicts in Naming Classes

Example: `09-name-conflict`

In Form we declare:

````
require_once __DIR__ . '/Validator.php';
require_once __DIR__ . '/ThirdPartyValidator.php';
````

The problem is that both classes use the same "Validator" Class, so when executing index.php this error will occur:

```
Fatal error: Cannot declare class Validator, because the name is already in use in 
/shared/httpd/my-drupal/web/example/lib/ThirdPartyValidator.php on line 3
```

Solution: Use **namespaces**

## Namespaces Example

Example: `11-namespaces`

Namespaces are like virtual directories.
When you define a namespace it is like defining a virtual directory.
When you want to use a namespace you will use the *use* statement.

### index.php

We tell to use *Builder* and *ContactUsController* Classes from the `BAM\OOPExampleSite` namespace.

```
use BAM\OOPExampleSite\Builder;
use BAM\OOPExampleSite\ContactUsController;
```

### Builder.php defines namespace

```
namespace BAM\OOPExampleSite;
```

### ContactUsController.php defines namespace

We define the same namespace as for Builder.php.

But we tell to use *PrintedPage* and *DefaultPage* 
from the `BAM\OOPExampleSite\Page` namespaces.

```
namespace BAM\OOPExampleSite;

use BAM\OOPExampleSite\Page\PrintedPage;
use BAM\OOPExampleSite\Page\DefaultPage;
```

### Page.php

```
namespace BAM\OOPExampleSite;
```

#### DefaultPage.php and PrintedPage.php

We define a special subdirectory namespace.
But we also tell to use the `BAM\OOPExampleSite\Page` class to be able to extend the Page Class.
 


```
namespace BAM\OOPExampleSite\Page;
use BAM\OOPExampleSite\Page;

class DefaultPage extends Page {...}
class PrintedPage extends Page {...}
```

### Form.php and Validator.php

They both use

```
namespace BAM\OOPExampleSite;
```

So the classes that they define belong to this namespaces.

When calling the Validator class from Form.php the Validator.php class will be used.

If we wanted to use the other validator we would use (It takes always the last Class defined).

```
use ThirdParty\Utilities\Validator;
```

If we want to use both Validators we could define
```
use ThirdParty\Utilities\Validator as OtherValidator;
```

Keeping in mind that the third party validator has this namespace defined:

```
namespace ThirdParty\Utilities;
```

To call the Validators you can use one of the following

```
// Using Full qualified names
\ThirdParty\Utilities\Validator::notEmpty($value);

// Using Aliases
OtherValidator::notEmpty($value);

// Using Use Statements
Validator::notEmpty($value);
```

## Autoloading

Instead of using *require* and *use* statements 
it would be great to just use the *use* statements 
so that the classes get auto-loaded.

### Creating an Autoloader

Example: `12-autoloader`


We add the following to our index.php

```
function my_autoloader($namespace)
{
  $namespace_array = explode("\\", $namespace);
  $class = end($namespace_array);
  $file_location = __DIR__ . '/lib/' . $class . '.php';
  include $file_location;
}

spl_autoload_register('my_autoloader');

```

This will be executed every time we execute a Class for the first time.
It is NOT a good idea to use your own autoloader.
You should use an third party autoloader.
Drupal uses caching for autoloading classes.

## PHP FIG / Drupal 8 and PSR-4 Autoloading

https://www.php-fig.org/

Standards to structure your PHP Application

PSR stands for PHP Standard Recommendation.

PSR-4 is for autoloading

Drupal 8 uses PSR-4

https://www.php-fig.org/psr/psr-4/

https://www.drupal.org/docs/develop/standards/psr-4-namespaces-and-autoloading-in-drupal-8 

So in Drupal our modules will have the following structure:
modules/$modulename/src/ 
with the following namespace 
Drupal\$modulename\

https://buildamodule.com/video/drupal-8-developer-prep-working-with-symfony-components-in-drupal-8-part-1-using-and-creating-services-why-hook-menu-was-removed-and-why-drupal-switched-to-psr-4-autoloading

## PSR-0 Example

Example: `13-PSR-0-Example`

This shows how to use our example with PSR-0 Autoloading (Drupal uses PSR-4 so it would have more simple directory structure)

# T5: Composer

 - Has autoloader out of the box
 - Can download packages from [packagist](https://packagist.org/)
   - Have a look at all the packages available. 
   - **It's amazing!** how you can integrate those packaged to your PHP Application.
 - It uses Semantic Versioning for version numbers. More info at [semver](https://semver.org/)
 
## Install

Example: `14-composer`

We put in our examples folder a `composer.json` file

and run `composer install`

packages will be automatically downloaded to the `vendor` directory and a `composer.lock` file will be generated.

### About JSON


JSON is JavaScript Object Notation: It basically stores information in JS Array Notation.

## Using composer

Example: `15-composer-use`

Once we have downloaded all the packages we can use them from our Php Application (index.php). We just:
 - Require our autoloader from composer.
 - Use our downloaded package using the *use* statement.

```
$loader = require 'vendor/autoload.php';

use Doctrine\Common\Inflector\Inflector;

$singular = 'mouse';
$plural = Inflector::pluralize($singular);

print 'The plural of ' . $singular . '  is ' . $plural . '. Squeek(s)!';
```

## Drupal 8 and composer

Drupal 8 uses composer in a similar way as we have seen in the previous example.

It has a composer.json file with the required packages and an autolaoad future

# T6: Symfony

Fabien Potencier created a nice tutorial called [Create your own framework... on top of the Symfony2 Components](http://fabien.potencier.org/create-your-own-framework-on-top-of-the-symfony2-components-part-1.html)
Updated Tutorial can be found at [symfony.com/doc/current/create_framework](https://symfony.com/doc/current/create_framework/index.html)

We will just progress through the original (obsolete) tutorial.

## Create your own PHP Framework

### Index.php without symfony

Example: `16-vanilla-php`

http://my-drupal.loc/example/?name=Pierre

We have to take care of
  - undefined name variable
  - js that could be injected
  - define character encoding
 
 
### Http Foundation Component

The first component that we will import is the Http Foundation Component. 
We will add a composer.json to require that component and install it with composer.
It will download more than 100 files in the `vendor` directory!

We will use 2 classes from this component:
 - Request.php
 - Response.php

```
Symfony Diagram:
REQUEST ----> Process Data ----> RESPONSE
```

Notes: 
 - You can output how you want the Response class (HTML, XML, JSON, etc.)
 - Using Http Foundation gives you access to Global Variables in a secure way

#### Request Examples

```
// Simulate a request:
$request = Request::create('/index.php?name=Fabien');

// The URI being requested
print $request->getPathInfo();
 
// Retrieve GET variables respectively
print $request->query->get('name');
print $request->query->get('name', 'Universe');
 
// Retrieve SERVER variables
print $request->server->get('HTTP_HOST');
 
// Retrieve an HTTP request header, with normalized, lowercase keys
print $request->headers->get('host');
 
print $request->getMethod(); // GET, POST, PUT, DELETE, HEAD
var_dump($request->getLanguages()); // An array of languages the client accepts


// Example with getting a client IP address to as a security check:
$request->getClientIp();


```

#### Response Examples

``` 
$response = new Response();
 
$response->setContent('Hello again, world, it is ' . time() . '!');
$response->setStatusCode(404);
$response->headers->set('Content-Type', 'text/plain');// Renders the page as text, not as html
 
// configure the HTTP cache headers
$response->setMaxAge(10);
```

#### Creating an init file

Example: `18-symfony-init-file`
http://my-drupal.loc/example/index.php
http://my-drupal.loc/example/bye.php

We create a init file that creates the Request and Response classes.
Now we have 2 pages that uses that init file.

#### Creating a Front Controller

Example: `19-symfony-front-controller`

We want to route all request to one file. 
We will add an .htaccess that redirects all traffic to front.php

In that file we define the `/hello` and `/bye` routes.

Now `hello.php` and `bye.php` just define a response class, 
they do not require the `init.php` nor do they need to send the response.

#### Converting our pages to php templates

Example: `20-symfony-php-templates`

Now we use in the front.php the following code that turn on the output buffer from standard php:

```
ob_start();
include $map[$path];
$response->setContent(ob_get_clean());
```

In the pages we just use php when we need to access to variables.
Later on we will use twig, so that we do not need to use php templates anymore.

#### Using Variables in php templates

Example: `21-symfony-php-variables`

To use variables in php templates we can use the *extract* function.
We extract the variables from our front.php and use them in the hello.php page.

If $name is not defined it will still give us some error.
To test this go to http://example.loc/hello?name=world

### Routing Component

Example: `22-symfony-routing-component`

Our routing code in *front.php* is very rudimentary. 
Let's use the symfony routing component for that.

We will import it updating our composer.json file.

To do that we run `composer update` instead of `composer install`.

We move our routing to a separate file called `app.php`.
Now we need to `use Symfony\Component\Routing;` in app.php and front.php.

We now use a matcher class to match routes.
We also have added some examples howto generate URL's.

#### Adding a Render Template Controller

Example: `23-symfony-render-template-controller`

Now we add an controller to the front.php class.
We also need to pass that controller name in our app.php class.

It uses Closures/Anonymous functions (in JS it is called callback).

As an example we will add a new route called `/is_leap_year/{year}`

### Http Kernel Controller

Example: `24-symfony-http-kernel-component`

At our app.php we instantiate for every route a new controller. 
Let's improve that with the Http Kernel Controller Component (it is not only a controller resolver).

We add the $resolver and $controller to our front.php class.


### Creating our Framework

Example: `25-symfony-framework`

We create a new Framework called Simplex. 
It will be at /src/Simplex/Framework.php.

Now we pass our $matcher and $resolver to our framework.

Now our code is easier to understand :)

We also add our Framework to composer to be autoloaded (using PSR-0 (keep in mind that D8 uses PSR-4))

````
"autoload": {
  "psr-0": { "Simplex": "src/" }
}
````

As usual you need to run composer update to make this happen.


#### Model and Controller class

Example: `26-calendar-namnespace`

We improve app.php so that it uses an independent controller class.

We create the `calendar` namespace with 2 classes:
 - LeapYear (Model Class)
 - LeapYearController (Controller Class): This will use the Model Class.
 
We also update our composer.json so that the new package is PSR-0 compatible

# T7: Unit Testing

## Introduction

We will use *PHP unit* to test our classes.

Common types of testing:
 - Unit Testing
 - Functional Testing:
   - For Drupal we do it in the Browser usually with *Simple Test* or *[Behat](https://blog.eleven-labs.com/en/behat-structure-functional-tests/)*
   - For JS we can use Selenium
 - Regression Testing: Makes sure that a bug does not come back (you would test this using unit or functional testing).
 

To test our Application we will have:
 - Test files at  `/src/Simplex/Tests`
 - One Test configuration file at `/phpunit.xml`

To test our application just run `phpunit` from `/`

The tricky part is `getMockBuilder` that will get an interface to know the methods that can be tested.

After having instances of $matcher and $resolver using the getMockBuilder we use DI to create an instance of our Framework.

## Dependency Injection (DI)

We have created instances of $matcher and $resolver from outside of our code. That is DI!!!
We are passing *mock objects*.

Right now when calling our Framework from our Test class we get an error because we created mock objects instead of *UrlMatcher* and *ControllerResolver* classes respectively.

So we need to *type-hint* our mock classes!!!

To do so we just need to change our Framework to use the UrlMatcherInterface and ContollerResolverInterface instead of the previous ones.

This can be done do because the previous classes extend the Inter;

## About our Test class

 - any method that begins with test* will be run as a test.
 
## About what can be tested

[Manual](https://phpunit.de/manual/6.5/en/writing-tests-for-phpunit.html)
[Assertions](https://phpunit.de/manual/6.5/en/appendixes.assertions.html)






Videos that needs update

---

07 - 17 , 23
11 - 4 , 6
12 - 1 , 3 , 7
14 - 15 , 17 14 ,25 , 4
 

 T7
 V12
