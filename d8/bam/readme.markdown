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


## Oop Example

Example: `05-oop-form`

Tips: 
 - Be Agile (adaptable code) but
   - Only create new Object Types when you need them!
   - DRY: Do not repeat yourself!



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
 

 T3
 V15

