# BAM - Drupal 8 Developer Preparation

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
 
# T1 Build a dummy module

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

# T2 : OOP

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


# T3: Namespacing and Autoloading



 
---

Videos that needs update

06 - 3
07 - 17
07 - 23
11 - 4
11 - 6
12 - 1
12 - 3
12 - 7
14 - 15
14 - 17
14 - 25
14 - 4
 

 T4
 V1

