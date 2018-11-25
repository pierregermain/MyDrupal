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

 # Tagged Services

 - Typically, these are picked up by a collector service

 Example:

```
tags: 
  - {name: tag_name}
```


# Using services in Drupal 8

2 ways:
 1. statically by a static call to the Service Container,
 2. injected using dependency injection to pass the object through the constructor (or in some rare cases, a setter method).

