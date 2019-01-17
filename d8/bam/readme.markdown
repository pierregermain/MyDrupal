# BAM - Drupal 8 Developer Preparation

We will cover:
 - Build a simple module
 - OOP
 - Namespacing
 - Autolading
 - Symfony integration
 - Controllers
 - Composer
 - Custom Code
 - Migrate Modules
 - Unit and functional testing
 - Plugins
 
# Build a dummy module

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
   - `/src/Controller/mycontroller.php`
   - `routing.yml`

We can add a Controller using the Drupal Console. It will add the following files
```
 modules/glue/src/Controller/MyController.php
 modules/glue/glue.routing.yml
```

Now, if you go to `glue/hello/hola` it will print `hola`.



 
 T2
 V4
