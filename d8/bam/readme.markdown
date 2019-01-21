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

Instead of using *require* and *use* statements just use the *use* statements so that the classes will autoload.

### Creating an Autoloader





---

Videos that needs update

06 - 3
07 - 17 , 23
11 - 4 , 6
12 - 1 , 3 , 7
14 - 15 , 17 14 ,25 , 4
 

 T4
 V7
